<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
     public function show($token)
    {

        return view('auth.reset-password',[
            'token'=>$token
        ]);

    }



    public function update(Request $request)
    {


        $request->validate([

            'token'=>'required',

            'email'=>'required|email',

            'password'=>'required|min:8|confirmed'

        ]);



        $status = Password::reset(

            $request->only(
                'email',
                'password',
                'password_confirmation',
                'token'
            ),


            function($user,$password){

                $user->forceFill([

                    'password'=>Hash::make($password)

                ])->save();


            }

        );



        return $status === Password::PASSWORD_RESET

            ? redirect('/login')
                ->with('success','Mot de passe modifié avec succès.')

            : back()->withErrors([
                'email'=>'Impossible de réinitialiser le mot de passe.'
            ]);

    }
}
