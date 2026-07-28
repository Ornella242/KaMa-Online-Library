<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use App\Models\Role;
use App\Models\Country;
use App\Services\PurchaseClaimService;

use Illuminate\Http\Request;

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
            'password' => 'required'
        ]);

        $remember = $request->boolean('remember');


        if (Auth::attempt($credentials, $remember)) {
            
            $request->session()->regenerate();

            $user = Auth::user();
            app(PurchaseClaimService::class)->claimFor($user);

            if ($user->role->name === 'admin') {
                return redirect('/admin/dashboard');
            }

            if ($user->role->name === 'writer') {
                return redirect('/writer/dashboard');
            }

            return redirect()->route('reader.account');
        }

        return back()->withErrors([
            'email' => 'Identifiants incorrects.',
        ])->onlyInput('email');
    }


    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Déconnexion réussie !');
    }

}
