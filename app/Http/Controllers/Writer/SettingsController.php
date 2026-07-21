<?php

namespace App\Http\Controllers\Writer;

use App\Http\Controllers\Controller;
use App\Models\NotificationSetting;
use Illuminate\Http\Request;
use App\Models\SocialProfile;
use App\Models\Country;
use Illuminate\Support\Facades\Auth;


class SettingsController extends Controller
{
    public function index()
    {
       $social = SocialProfile::firstOrNew([
            'user_id' => Auth::id(),
        ]);

    $settings = NotificationSetting::firstOrNew(
        ['user_id' => Auth::id()],
        [
            'role' => Auth::user()->role->name,
            'settings' => [],
        ]
    );
    $countries = Country::orderBy('name')->get();


    return view('writer.settings', compact('social', 'settings','countries'));
    }
}


