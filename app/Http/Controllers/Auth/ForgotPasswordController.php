<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    
    public function show()
    {
        return view('auth.forgot-password');
    }



    public function send(Request $request)
    {

        $request->validate([
            'email'=>'required|email'
        ]);



        $status = Password::sendResetLink(
            $request->only('email')
        );



        return $status === Password::RESET_LINK_SENT

            ? back()->with(
                'success',
                'Un lien de réinitialisation a été envoyé.'
            )

            : back()->withErrors([
                'email'=>'Cette adresse email est introuvable.'
            ]);

    }
}
