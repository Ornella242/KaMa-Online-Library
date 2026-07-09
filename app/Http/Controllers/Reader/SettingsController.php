<?php

namespace App\Http\Controllers\Reader;

use App\Http\Controllers\Controller;
use App\Models\NotificationSetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function indexReader()
    {

    $settings = NotificationSetting::firstOrCreate([
        'user_id' => Auth::id(),
        'role' => Auth::user()->role->name,
    ]);

    return view('reader.settings', compact('settings'));
    }
}
