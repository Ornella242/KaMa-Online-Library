<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
     public function index()
    {
        $countries = Country::orderBy('name')->get();
        return view('admin.settings', compact('countries'));
    }
}
