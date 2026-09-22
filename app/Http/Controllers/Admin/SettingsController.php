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
    public function index(Request $request)
    {
        $countries = Country::orderBy('name')->get();
        $publicationFees = PublicationFee::query()
            ->get()
            ->keyBy('book_type');

        $withdrawalCommissionPercent = (float) Setting::getValue('withdrawal_commission_percent', 5);
        $withdrawalMinimumAmount = (float) Setting::getValue('withdrawal_minimum_amount', 10);
        $pawaPayRates = app(\App\Services\PawaPayService::class)->rates();
        $pawaPayCurrencyMeta = $this->pawaPayCurrencyMeta();
        $activeTab = $this->resolveSettingsTab($request->query('tab'));

        return view('admin.settings', compact(
            'countries',
            'publicationFees',
            'withdrawalCommissionPercent',
            'withdrawalMinimumAmount',
            'pawaPayRates',
            'pawaPayCurrencyMeta',
            'activeTab'
        ));
    }

    public function updatePublicationFees(Request $request)
    {
        $validated = $request->validate([
            'ebook_amount' => ['required', 'numeric', 'min:0.01', 'max:999999'],
            'audio_amount' => ['required', 'numeric', 'min:0.01', 'max:999999'],
            'currency' => ['required', 'in:EUR'],
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

        return redirect()
            ->route('admin.settings', ['tab' => 'commerce'])
            ->with('success', 'Les frais de publication ont été mis à jour.');
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

        return redirect()
            ->route('admin.settings', ['tab' => 'commerce'])
            ->with('success', 'Les paramètres de retrait ont été mis à jour.');
    }

    public function updatePawaPayRates(Request $request)
    {
        $rates = $request->input('rates', []);
        if (! is_array($rates)) {
            return redirect()
                ->route('admin.settings', ['tab' => 'momo'])
                ->withErrors(['rates' => 'Format de taux invalide.']);
        }

        $cleaned = [];
        foreach ($rates as $currency => $rate) {
            $currency = strtoupper((string) $currency);
            if (! preg_match('/^[A-Z]{3}$/', $currency)) {
                continue;
            }
            if (! is_numeric($rate) || (float) $rate <= 0) {
                return redirect()
                    ->route('admin.settings', ['tab' => 'momo'])
                    ->withInput()
                    ->withErrors(['rates' => "Taux invalide pour {$currency}."]);
            }
            $cleaned[$currency] = round((float) $rate, 6);
        }

        Setting::setValue('pawapay_fx_rates', json_encode($cleaned));

        return redirect()
            ->route('admin.settings', ['tab' => 'momo'])
            ->with('success', 'Les taux Mobile Money (EUR → devise locale) ont été mis à jour.');
    }

    /**
     * @return array<string, array{label:string,countries:string}>
     */
    private function pawaPayCurrencyMeta(): array
    {
        $meta = [];

        foreach ((array) config('pawapay.markets', []) as $market) {
            $currency = strtoupper((string) ($market['currency'] ?? ''));
            if ($currency === '') {
                continue;
            }

            $meta[$currency] ??= [
                'label' => $this->currencyLabel($currency),
                'countries' => [],
            ];

            $name = trim((string) ($market['name'] ?? ''));
            if ($name !== '' && ! in_array($name, $meta[$currency]['countries'], true)) {
                $meta[$currency]['countries'][] = $name;
            }
        }

        foreach ($meta as $currency => $row) {
            $meta[$currency]['countries'] = implode(', ', $row['countries']);
        }

        return $meta;
    }

    private function currencyLabel(string $currency): string
    {
        return match ($currency) {
            'XOF' => 'Franc CFA Ouest',
            'XAF' => 'Franc CFA Centre',
            'GHS' => 'Cedi ghanéen',
            'NGN' => 'Naira',
            'KES' => 'Shilling kenyan',
            'UGX' => 'Shilling ougandais',
            'TZS' => 'Shilling tanzanien',
            'RWF' => 'Franc rwandais',
            'ZMW' => 'Kwacha zambien',
            'MWK' => 'Kwacha malawite',
            'MZN' => 'Metical',
            'CDF' => 'Franc congolais',
            'ETB' => 'Birr',
            'LSL' => 'Loti',
            'SLE' => 'Leone',
            default => $currency,
        };
    }

    private function resolveSettingsTab(?string $tab): string
    {
        $allowed = ['commerce', 'momo', 'profil', 'securite'];

        return in_array($tab, $allowed, true) ? $tab : 'commerce';
    }
}
