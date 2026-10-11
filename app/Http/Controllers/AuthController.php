<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Role;
use App\Models\User;
use App\Notifications\LoginOtpNotification;
use App\Services\PurchaseClaimService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Throwable;

class AuthController extends Controller
{

    public function showRegister()
    {
        $countries = Country::orderBy('name')->get();
        $roles = \App\Models\Role::query()
            ->where('name', '!=', 'admin')
            ->get();
        return view('auth.register', compact('roles','countries'));
    }

    public function register(Request $request)
    {

        $validated = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname'  => 'required|string|max:255',
            'email'     => 'required|email|max:255|unique:users,email',
            'country_id' => [
                'required',
                'exists:countries,id'
            ],
            'city'   => 'required|string|max:255',
            'role_id'   => 'required|exists:roles,id',
            'password'  => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'firstname' => $validated['firstname'],
            'lastname'  => $validated['lastname'],
            'email'     => $validated['email'],
            'country_id'   => $validated['country_id'],
            'city'   => $validated['city'],
            'role_id'   => $validated['role_id'],
            'password'  => Hash::make($validated['password']),
        ]);

        Auth::login($user);

        $request->session()->regenerate();

        app(PurchaseClaimService::class)->claimFor($user);

        event(new Registered($user));

        return redirect()->route('verification.notice')
            ->with('success', 'Compte créé ! Vérifiez votre adresse email.');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $remember = $request->boolean('remember');

        // Vérification des identifiants sans connexion immédiate
        if (! Auth::validate($credentials)) {
            return back()->withErrors([
                'email' => 'Identifiants incorrects.',
            ])->onlyInput('email');
        }

        $user = User::where('email', $credentials['email'])->firstOrFail();

        // Génération du code OTP à 6 chiffres et envoi par email
        $code = $user->generateTwoFactorCode();

        try {
            $user->notify(new LoginOtpNotification($code));
        } catch (Throwable $e) {
            report($e);
        }

        // Sauvegarde de l'état d'authentification 2FA en attente
        $request->session()->put('auth.2fa', [
            'user_id' => $user->id,
            'remember' => $remember,
            'sent_at' => now()->timestamp,
        ]);

        return redirect()->route('auth.2fa.index')
            ->with('success', 'Un code de confirmation à 6 chiffres a été envoyé à votre adresse e-mail.');
    }

    /**
     * Page de saisie du code OTP à 6 chiffres.
     */
    public function showVerifyTwoFactor(Request $request)
    {
        if (Auth::check()) {
            return $this->redirectAfterAuth(Auth::user());
        }

        $userId = $request->session()->get('auth.2fa.user_id');

        if (! $userId) {
            return redirect()->route('login')
                ->with('info', 'Veuillez renseigner vos identifiants pour recevoir un code de vérification.');
        }

        $user = User::find($userId);

        if (! $user) {
            $request->session()->forget('auth.2fa');

            return redirect()->route('login');
        }

        $sentAt = (int) $request->session()->get('auth.2fa.sent_at', 0);
        $cooldownRemaining = max(0, 60 - (time() - $sentAt));

        return view('auth.verify-2fa', [
            'user' => $user,
            'maskedEmail' => $user->maskedEmail(),
            'cooldownRemaining' => $cooldownRemaining,
        ]);
    }

    /**
     * Validation du code OTP et finalisation de la connexion.
     */
    public function verifyTwoFactor(Request $request)
    {
        $userId = $request->session()->get('auth.2fa.user_id');

        if (! $userId) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Session expirée. Veuillez vous reconnecter.']);
        }

        $user = User::find($userId);

        if (! $user) {
            $request->session()->forget('auth.2fa');

            return redirect()->route('login');
        }

        $request->validate([
            'code' => ['required', 'string'],
        ], [
            'code.required' => 'Veuillez saisir le code à 6 chiffres reçu par e-mail.',
        ]);

        $code = trim(str_replace(' ', '', (string) $request->input('code')));

        if (! $user->two_factor_expires_at || $user->two_factor_expires_at->isPast()) {
            return back()->withErrors([
                'code' => 'Ce code de vérification a expiré. Veuillez cliquer sur « Renvoyer un nouveau code ».',
            ]);
        }

        if (! $user->isValidTwoFactorCode($code)) {
            return back()->withErrors([
                'code' => 'Code de vérification incorrect. Veuillez vérifier vos e-mails et réessayer.',
            ]);
        }

        // Le code est valide : nettoyage
        $user->clearTwoFactorCode();

        $remember = (bool) $request->session()->get('auth.2fa.remember', false);
        $request->session()->forget('auth.2fa');

        Auth::login($user, $remember);
        $request->session()->regenerate();

        app(PurchaseClaimService::class)->claimFor($user);

        return $this->redirectAfterAuth($user);
    }

    /**
     * Renvoi d'un nouveau code OTP par email avec cooldown de 60 secondes.
     */
    public function resendTwoFactor(Request $request)
    {
        $userId = $request->session()->get('auth.2fa.user_id');

        if (! $userId) {
            return redirect()->route('login');
        }

        $user = User::find($userId);

        if (! $user) {
            $request->session()->forget('auth.2fa');

            return redirect()->route('login');
        }

        $sentAt = (int) $request->session()->get('auth.2fa.sent_at', 0);
        $secondsSince = time() - $sentAt;

        if ($secondsSince < 60) {
            $remaining = 60 - $secondsSince;

            return back()->with('info', "Veuillez patienter encore {$remaining} seconde(s) avant de demander un nouveau code.");
        }

        $code = $user->generateTwoFactorCode();

        try {
            $user->notify(new LoginOtpNotification($code));
        } catch (Throwable $e) {
            report($e);
        }

        $request->session()->put('auth.2fa.sent_at', now()->timestamp);

        return back()->with('success', 'Un nouveau code de vérification à 6 chiffres a été envoyé à votre adresse e-mail.');
    }

    /**
     * Annule la tentative 2FA et permet à l'utilisateur de changer de compte.
     */
    public function cancelTwoFactor(Request $request)
    {
        $userId = $request->session()->get('auth.2fa.user_id');

        if ($userId) {
            $user = User::find($userId);
            $user?->clearTwoFactorCode();
        }

        $request->session()->forget('auth.2fa');

        return redirect()->route('login')->with('info', 'Connexion annulée.');
    }

    /**
     * Redirection appropriée selon le rôle de l'utilisateur après connexion.
     */
    private function redirectAfterAuth(User $user)
    {
        if ($user->isAdmin()) {
            $landingRoute = $user->adminLandingRoute();

            if ($landingRoute) {
                return redirect()->to($landingRoute);
            }

            Auth::logout();

            return redirect()
                ->route('login')
                ->withErrors([
                    'email' => 'Votre compte administrateur ne dispose d’aucun accès.',
                ]);
        }

        if ($user->isWriter()) {
            return redirect()->route('writer.dashboard');
        }

        return redirect()->route('reader.account');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Déconnexion réussie !');
    }

}
