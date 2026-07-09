<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SocialProfile;
use Illuminate\Support\Facades\Auth;

class SocialProfileController extends Controller
{
    public function storeOrUpdate(Request $request)
    {
        $request->validate([
            'facebook_url' => 'nullable|url',
            'x_url' => 'nullable|url',
            'instagram_url' => 'nullable|url',
            'linkedin_url' => 'nullable|url',
        ]);

        $social = SocialProfile::updateOrCreate(
            ['user_id' => Auth::id()],
            $request->only([
                'facebook_url',
                'x_url',
                'instagram_url',
                'linkedin_url'
            ])
        );

        return back()->with('success', 'Réseaux sociaux mis à jour avec succès.');
    }

    // public function edit()
    // {
    //     $social = SocialProfile::firstOrCreate(
    //         ['user_id' => Auth::id()]
    //     );

    //     return view('writer.settings', compact('social'));
    // }
}
