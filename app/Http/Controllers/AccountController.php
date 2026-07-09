<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


class AccountController extends Controller
{
    public function account()
    {
        $user = Auth::user();

        $score = 0;

        if ($user->hasVerifiedEmail()) $score += 25;
        if ($user->phone) $score += 20;
        if ($user->gender) $score += 15;
        if ($user->avatar) $score += 20;
        if ($user->country) $score += 20;
        if ($user->firstname && $user->lastname) $score += 20;

        $score = min($score, 100);

        return view('reader.profile', compact('user', 'score'));
    }

      public function accountWriter()
    {
        return view('writer.dashboard');
    }
}
