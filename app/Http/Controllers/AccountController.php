<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Services\PurchaseClaimService;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function __construct(private PurchaseClaimService $claims)
    {
    }

    public function account()
    {
        $user = Auth::user()->load('country');
        $this->claims->claimFor($user);

        $score = 0;
        if ($user->hasVerifiedEmail()) {
            $score += 25;
        }
        if ($user->phone) {
            $score += 20;
        }
        if ($user->gender) {
            $score += 15;
        }
        if ($user->avatar) {
            $score += 15;
        }
        if ($user->country_id) {
            $score += 15;
        }
        if ($user->city) {
            $score += 10;
        }

        $score = min($score, 100);
        $countries = Country::query()->orderBy('name')->get();

        return view('reader.profile', compact('user', 'score', 'countries'));
    }
}
