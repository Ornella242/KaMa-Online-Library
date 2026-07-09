<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
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
            'firstname' => 'required|string',
            'lastname'  => 'required|string',
            'email'     => 'required|email',
            'country'   => 'required|string',
            'phone'     => 'nullable|string',
            'bio' => 'nullable|string|max:255',
            'gender'    => 'nullable|in:male,female,other',
        ]);

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $path;
        }

        $user->update($validated);

        return back()->with('success', 'Profil mis à jour');
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
