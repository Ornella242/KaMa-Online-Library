<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\PublicationFee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingsController extends Controller
{
     public function index()
    {
        $countries = Country::orderBy('name')->get();
        $publicationFees = PublicationFee::query()
            ->get()
            ->keyBy('book_type');

        return view('admin.settings', compact('countries', 'publicationFees'));
    }

    public function updatePublicationFees(Request $request)
    {
        $validated = $request->validate([
            'ebook_amount' => ['required', 'integer', 'min:1', 'max:999999'],
            'audio_amount' => ['required', 'integer', 'min:1', 'max:999999'],
            'currency' => ['required', 'in:XOF'],
        ]);

        DB::transaction(function () use ($validated) {
            foreach (['ebook', 'audio'] as $bookType) {
                PublicationFee::query()->updateOrCreate(
                    ['book_type' => $bookType],
                    [
                        'amount' => $validated[$bookType.'_amount'],
                        'currency' => strtoupper($validated['currency']),
                    ]
                );
            }
        });

        return back()->with('success', 'Les frais de publication ont été mis à jour.');
    }
}
