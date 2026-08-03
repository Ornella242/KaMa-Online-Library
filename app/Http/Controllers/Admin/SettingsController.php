<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\PublicationFee;
use App\Models\Setting;
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

        $withdrawalCommissionPercent = (float) Setting::getValue('withdrawal_commission_percent', 5);
        $withdrawalMinimumAmount = (float) Setting::getValue('withdrawal_minimum_amount', 10);

        return view('admin.settings', compact(
            'countries',
            'publicationFees',
            'withdrawalCommissionPercent',
            'withdrawalMinimumAmount'
        ));
    }

    public function updatePublicationFees(Request $request)
    {
        $validated = $request->validate([
            'ebook_amount' => ['required', 'numeric', 'min:0.01', 'max:999999'],
            'audio_amount' => ['required', 'numeric', 'min:0.01', 'max:999999'],
            'currency' => ['required', 'in:USD'],
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

    public function updateWithdrawalSettings(Request $request)
    {
        $validated = $request->validate([
            'withdrawal_commission_percent' => ['required', 'numeric', 'min:0', 'max:50'],
            'withdrawal_minimum_amount' => ['required', 'numeric', 'min:1', 'max:999999'],
        ]);

        Setting::setValue(
            'withdrawal_commission_percent',
            round((float) $validated['withdrawal_commission_percent'], 2)
        );
        Setting::setValue(
            'withdrawal_minimum_amount',
            round((float) $validated['withdrawal_minimum_amount'], 2)
        );

        return back()->with('success', 'Les paramètres de retrait ont été mis à jour.');
    }
}
