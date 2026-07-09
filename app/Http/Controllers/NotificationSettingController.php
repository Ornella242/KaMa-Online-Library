<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NotificationSetting;
use Illuminate\Support\Facades\Auth;

class NotificationSettingController extends Controller
{
    public function update(Request $request)
    {
        $user = Auth::user();

        // SAFE ROLE
        $role = $user->role?->name ?? $user->role;

        $data = match ($role) {

            'writer' => [
                'book_sold' => $request->boolean('book_sold'),
                'ad_approved' => $request->boolean('ad_approved'),
                'book_review' => $request->boolean('book_review'),
            ],

            'admin' => [
                'new_book_published' => $request->boolean('new_book_published'),
                'book_sold' => $request->boolean('book_sold'),
                'ad_request' => $request->boolean('ad_request'),
            ],

            'reader' => [
                'recommendations' => $request->boolean('recommendations'),
                'favorite_author_books'=> $request->boolean('favorite_author_books'),
                'reading_reminders'    => $request->boolean('reading_reminders'),
                'purchase_confirmation' => $request->boolean('purchase_confirmation'),
            ],

            default => [],
        };

        
        NotificationSetting::updateOrCreate(
            [
                'user_id' => $user->id,
                'role' => $role,
            ],
            [
                'settings' => $data
            ]
        );
        return back()->with('success', 'Notifications mises à jour');
    }
}
