<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Mail\UserPasswordMail;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Country;
use App\Models\Role;
use App\Models\Payment;
use App\Models\Wallet;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $totalUsers = User::count();
        $totalReaders = User::whereHas('role', fn ($q) => $q->where('name', 'reader'))->count();
        $totalWriters = User::whereHas('role', fn ($q) => $q->where('name', 'writer'))->count();
        $totalAdmins = User::whereHas('role', fn ($q) => $q->where('name', 'admin'))->count();

        $query = User::with(['role', 'country']);

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('firstname', 'like', "%{$search}%")
                    ->orWhere('lastname', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('type') && in_array($request->type, ['reader', 'writer', 'admin'], true)) {
            $query->whereHas('role', function ($q) use ($request) {
                $q->where('name', $request->type);
            });
        }

        $users = $query
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $roles = Role::orderBy('name')->get();
        $countries = Country::orderBy('name')->get();

        return view('admin.users.users', compact(
            'users',
            'totalUsers',
            'totalReaders',
            'totalWriters',
            'totalAdmins',
            'roles',
            'countries'
        ));
    }

    public function show(User $user)
    {
        $user->load([
            'role',
            'books',
            'payments'
        ]);

        $data = [];
        if($user->role->name === 'writer' || $user->role->name === 'admin'){
            $data['totalBooks'] = $user->books()->count();
            $data['BooksUnderreview'] = $user->books()->where('status','under_review')->count();
            $data['totalSales'] = $user->payments()
                ->where('type','purchase')
                ->count();
            $data['totalRevenue'] = $user->payments()
                ->where('type','purchase')
                ->sum('amount');

            $data['books'] = $user->books()
                ->latest()
                ->get();
        }


        return view(
            'admin.users.show',
            compact('user','data')
        );
    }

    public function edit(User $user)
    {
        $countries = Country::orderBy('name')->get();
        return view('admin.users.edit', compact('user','countries'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname'  => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($user->id),
            ],
            'phone' => 'nullable|string|max:30',
            'country_id' => [
                'nullable',
                'exists:countries,id'
            ],
            'city' => [
                'nullable',
                'string',
                'max:100'
            ],
            'gender' => 'nullable|in:male,female,other',
            'bio' => 'nullable|string',
        ]);

        $user->update([
            'firstname' => $request->firstname,
            'lastname'  => $request->lastname,
            'email'     => $request->email,
            'phone'     => $request->phone,
            'country_id' => $request->country_id,
            'city' => $request->city,
            'gender'    => $request->gender,
            'bio'       => $request->bio,
        ]);

        return redirect()
            ->route('admin.users')
            ->with('success', 'Utilisateur modifié avec succès.');
    }

    public function destroy(User $user)
    {
        // Empêcher la suppression de son propre compte
        if ($user->id === Auth::id()) {

            return back()->with(
                'error',
                'Vous ne pouvez pas supprimer votre propre compte.'
            );
        }

        $user->delete();

        return redirect()
            ->route('admin.users')
            ->with(
                'success',
                'Utilisateur supprimé avec succès.'
            );
    }

    public function create()
    {
        $roles = Role::all();

        return view(
            'admin.users.create',
            compact('roles')
        );
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'firstname' => [
                'required',
                'string',
                'max:255',
            ],

            'lastname' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'unique:users,email',
            ],

            'role_id' => [
                'required',
                'exists:roles,id',
            ],
            'is_main_admin' => false,

            'admin_role_id' => [
                'nullable',
                'exists:roles,id',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:30',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Rôle principal
        |--------------------------------------------------------------------------
        */

        $primaryRole = Role::findOrFail($data['role_id']);

        /*
        |--------------------------------------------------------------------------
        | Le rôle principal doit obligatoirement être :
        | reader / writer / admin
        |--------------------------------------------------------------------------
        */

        if (!in_array($primaryRole->name, [
            'reader',
            'writer',
            'admin',
        ])) {

            return back()
                ->withErrors([
                    'role_id' => 'Le rôle sélectionné est invalide.',
                ])
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | Rôle administratif
        |--------------------------------------------------------------------------
        */

        $adminRole = null;


        /*
        |--------------------------------------------------------------------------
        | Si le compte est administrateur,
        | un rôle administratif doit être sélectionné.
        |--------------------------------------------------------------------------
        */

        if ($primaryRole->name === 'admin') {

            if (empty($data['admin_role_id'])) {

                return back()
                    ->withErrors([
                        'admin_role_id' =>
                            'Veuillez sélectionner un rôle administratif.',
                    ])
                    ->withInput();
            }


            $adminRole = Role::findOrFail(
                $data['admin_role_id']
            );


            /*
            | Les rôles système ne peuvent pas
            | être utilisés comme rôles administratifs.
            */

            if (in_array($adminRole->name, [
                'reader',
                'writer',
                'admin',
            ])) {

                return back()
                    ->withErrors([
                        'admin_role_id' =>
                            'Ce rôle ne peut pas être utilisé comme rôle administratif.',
                    ])
                    ->withInput();
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Si le compte n'est pas admin,
        | aucun rôle administratif ne doit être utilisé.
        |--------------------------------------------------------------------------
        */

        if ($primaryRole->name !== 'admin') {

            $adminRole = null;
        }


        /*
        |--------------------------------------------------------------------------
        | Génération du mot de passe
        |--------------------------------------------------------------------------
        */

        $password = strtoupper(Str::random(1))
            . strtolower(Str::random(4))
            . rand(10, 99)
            . '!';


        /*
        |--------------------------------------------------------------------------
        | Création du compte + rôle administratif
        |--------------------------------------------------------------------------
        */

        $user = DB::transaction(function () use (
            $data,
            $primaryRole,
            $adminRole,
            $password
        ) {

            $user = User::create([

                'firstname' => $data['firstname'],

                'lastname' => $data['lastname'],

                'email' => $data['email'],

                'phone' => $data['phone'] ?? null,

                'role_id' => $primaryRole->id,

                'password' => Hash::make($password),

            ]);


            /*
            |--------------------------------------------------------------------------
            | Attribution du rôle administratif
            |--------------------------------------------------------------------------
            */

            if ($adminRole) {

                $user->adminRoles()->sync([
                    $adminRole->id,
                ]);
            }


            return $user;
        });


        /*
        |--------------------------------------------------------------------------
        | Envoi de l'email
        |--------------------------------------------------------------------------
        */

        Mail::to($user->email)
            ->send(
                new UserPasswordMail(
                    $user,
                    $password
                )
            );


        /*
        |--------------------------------------------------------------------------
        | Redirection
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('admin.users')
            ->with(
                'success',
                'Utilisateur créé et identifiants envoyés par email.'
            );
    }

    public function becomeWriter(Request $request)
    {
        $user = Auth::user();

        if ($user->isWriter()) {
            return redirect()
                ->route('writer.dashboard')
                ->with('success', 'Vous êtes déjà écrivain.');
        }

        $validated = $request->validate([
            'accept_fees' => ['accepted'],
            'accept_rights' => ['accepted'],
            'accept_terms' => ['accepted'],
            'confirm_text' => ['required', 'string'],
        ], [
            'accept_fees.accepted' => 'Vous devez accepter les frais de publication.',
            'accept_rights.accepted' => 'Vous devez confirmer détenir les droits sur vos contenus.',
            'accept_terms.accepted' => 'Vous devez accepter les règles de publication KaMa.',
            'confirm_text.required' => 'Veuillez taper ÉCRIVAIN pour confirmer.',
        ]);

        if (mb_strtoupper(trim($validated['confirm_text'])) !== 'ÉCRIVAIN') {
            return back()
                ->withErrors(['confirm_text' => 'Veuillez taper exactement ÉCRIVAIN pour confirmer.'])
                ->withInput();
        }

        $writerRole = Role::query()->where('name', 'writer')->firstOrFail();

        $user->update([
            'role_id' => $writerRole->id,
            'is_writer' => true,
        ]);

        if (! $user->wallet()->exists()) {
            Wallet::query()->create([
                'user_id' => $user->id,
                'balance' => 0,
                'currency' => 'EUR',
            ]);
        }

        return redirect()
            ->route('writer.dashboard')
            ->with('success', 'Bienvenue dans l’espace écrivain ! Votre espace auteur est prêt.');
    }

   public function updateProfile(Request $request)
{
    
    $user = Auth::user();

    $validated = $request->validate([

        'firstname' => [
            'required',
            'string',
            'max:255'
        ],

        'lastname' => [
            'required',
            'string',
            'max:255'
        ],

        'email' => [
            'required',
            'email',
            'max:255',
            Rule::unique('users', 'email')->ignore($user->id),
        ],

        'country_id' => [
            'required',
            'exists:countries,id'
        ],

        'city' => [
            'required',
            'string',
            'max:100'
        ],

        'phone' => [
            'nullable',
            'string',
            'max:50'
        ],

        'bio' => [
            'nullable',
            'string',
            'max:255'
        ],

        'gender' => [
            'nullable',
            'in:male,female,other'
        ],

        'avatar' => [
            'nullable',
            'image',
            'mimes:jpg,jpeg,png,webp',
            'max:2048',
        ],

    ]);

    if ($request->hasFile('avatar')) {
        $path = $request
            ->file('avatar')
            ->store('avatars', 'public');

        $validated['avatar'] = $path;
    }
    $user->update($validated);


    return back()->with(
        'success',
        'Profil mis à jour'
    );

}


    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = Auth::user();

        // Vérifie le mot de passe actuel
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'Le mot de passe actuel est incorrect.'
            ]);
        }

        // Met à jour le mot de passe
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Votre mot de passe a été modifié avec succès.');
    }

}
