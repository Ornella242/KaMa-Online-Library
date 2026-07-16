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
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index(Request $request)
    {

        // Nombre total utilisateurs
        $totalUsers = User::count();

        // Lecteurs = utilisateurs ayant acheté au moins un livre
        $totalReaders = User::whereHas('payments', function($query){
            $query->where('type', 'purchase');
        })->count();

        // Ecrivains
        // ici je suppose que role_id = 2 pour écrivain
        $totalWriters = User::whereHas('role', function($query){

            $query->where('name', 'writer');

        })->count();

        $query = User::with(['role', 'payments']);

        // recherche
        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('firstname', 'like', "%{$search}%")
                ->orWhere('lastname', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");

            });

        }

        // filtre
        if ($request->type == 'writer') {

            $query->whereHas('role', function ($q) {

                $q->where('name', 'writer');

            });

        }

        if ($request->type == 'reader') {

            $query
                ->whereHas('role', function ($q) {
                    $q->where('name', 'reader');
                })
                ->whereHas('payments', function ($q) {
                    $q->where('type', 'purchase');
                });

        }

        $users = $query
            ->latest()
            ->paginate(15)
            ->withQueryString();


        return view('admin.users.users', compact(
            'users',
            'totalUsers',
            'totalReaders',
            'totalWriters'
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
                'required',
                'exists:countries,id'
            ],
            'city' => [
                'required',
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

        return back()->with('success', 'Utilisateur modifié avec succès.');
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

        $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role_id' => 'required|exists:roles,id',
        ]);


        // Génération mot de passe sécurisé

        $password = strtoupper(Str::random(1))
            . strtolower(Str::random(4))
            . rand(10,99)
            . '!';


        $user = User::create([

            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'phone' => $request->phone,
            'role_id' => $request->role_id,
            'password' => Hash::make($password),
        ]);


        // Envoi email

        Mail::to($user->email)
            ->send(
                new UserPasswordMail(
                    $user,
                    $password
                )
            );


        return redirect()
            ->route('admin.users')
            ->with(
                'success',
                'Utilisateur créé et identifiants envoyés par email.'
            );

    }

    public function becomeWriter()
    {
        $user = Auth::user();

        $user->update([
            'is_writer' => true
        ]);

        return redirect('/writer/account')
            ->with('success', 'Vous êtes maintenant écrivain !');
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
            'max:255'
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
