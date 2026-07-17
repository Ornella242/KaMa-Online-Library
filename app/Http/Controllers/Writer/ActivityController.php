<?php

namespace App\Http\Controllers\Writer;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
     public function index()
    {
        $activities = Activity::query()->where('user_id', Auth::id())
            ->with('book')
            ->latest()
            ->paginate(10);
        return view('writer.activities', compact('activities'));

    }

    public function destroy(Activity $activity)
    {
        if($activity->user_id != Auth::id()){
            abort(403);
        }
        $activity->delete();

        return back()
            ->with('success','Activité supprimée avec succès.');

    }
}
