<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
     public function clear(Request $request)
    {
        $request->user()
            ->notifications()
            ->delete();


        return back()
            ->with('success','Notifications supprimées.');
    }
}
