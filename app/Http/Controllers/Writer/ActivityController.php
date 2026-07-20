<?php

namespace App\Http\Controllers\Writer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $notifications = $user->notifications()
            ->latest()
            ->paginate(15);

        $user->unreadNotifications->markAsRead();

        return view('writer.activities', compact('notifications'));
    }

    public function destroy(DatabaseNotification $notification)
    {
        abort_unless($notification->notifiable_id === Auth::id(), 403);

        $notification->delete();

        return back()->with('success', 'Notification supprimée.');
    }
}
