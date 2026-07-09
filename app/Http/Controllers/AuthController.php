<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use App\Models\Role;

use Illuminate\Http\Request;

class AuthController extends Controller
{

    public function showRegister()
    {
        $roles = \App\Models\Role::query()
            ->where('name', '!=', 'admin')
            ->get();
        return view('auth.register', compact('roles'));
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname'  => 'required|string|max:255',
            'email'     => 'required|email|max:255|unique:users,email',
            'country'   => 'required|string|max:255',
            'role_id'   => 'required|exists:roles,id',
            'password'  => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'firstname' => $validated['firstname'],
            'lastname'  => $validated['lastname'],
            'email'     => $validated['email'],
            'country'   => $validated['country'],
            'role_id'   => $validated['role_id'],
            'password'  => Hash::make($validated['password']),
        ]);

        Auth::login($user);

        $request->session()->regenerate();

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
        if (Auth::attempt($credentials)) {

            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->role->name === 'admin') {
                return redirect('/admin');
            }

            if ($user->role->name === 'writer') {
                return redirect('/writer/dashboard');
            }

            return redirect('/reader/account');
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
