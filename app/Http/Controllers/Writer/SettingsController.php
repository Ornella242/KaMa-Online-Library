<?php

namespace App\Http\Controllers\Writer;

use App\Http\Controllers\Controller;
use App\Models\NotificationSetting;
use Illuminate\Http\Request;
use App\Models\SocialProfile;
use Illuminate\Support\Facades\Auth;


class SettingsController extends Controller
{
    public function index()
    {
       $social = SocialProfile::firstOrCreate([
        'user_id' => Auth::id(),
    ]);

    $settings = NotificationSetting::firstOrCreate([
        'user_id' => Auth::id(),
        'role' => Auth::user()->role->name,
    ]);

    return view('writer.settings', compact('social', 'settings'));
    }
}


