<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\PublicationFee;
use App\Models\Setting;
use App\Services\CurrencyFreaksService;
use App\Services\PawaPayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class SettingsController extends Controller
{
    public function index(Request $request, PawaPayService $pawaPay, CurrencyFreaksService $currencyFreaks)
    {
        $countries = Country::orderBy('name')->get();
        $publicationFees = PublicationFee::query()
            ->get()
            ->keyBy('book_type');

        $withdrawalCommissionPercent = (float) Setting::getValue('withdrawal_commission_percent', 5);
        $withdrawalMinimumAmount = (float) Setting::getValue('withdrawal_minimum_amount', 10);
        $pawaPayCurrencyMeta = $this->pawaPayCurrencyMeta();
        $activeTab = $this->resolveSettingsTab($request->query('tab'));

        $pawaPayRates = [];
        $fxMeta = [
            'date' => null,
            'source' => null,
            'configured' => $currencyFreaks->isConfigured(),
            'error' => null,
        ];

        try {
            $payload = $pawaPay->ratesMeta();
            $pawaPayRates = (array) ($payload['rates'] ?? []);
            $fxMeta['date'] = $payload['date'] ?? null;
            $fxMeta['source'] = $payload['source'] ?? null;
        } catch (Throwable $exception) {
            $fxMeta['error'] = $exception->getMessage();
        }

        return view('admin.settings', compact(
            'countries',
            'publicationFees',
            'withdrawalCommissionPercent',
            'withdrawalMinimumAmount',
            'pawaPayRates',
            'pawaPayCurrencyMeta',
            'activeTab',
            'fxMeta'
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

    public function refreshPawaPayRates(PawaPayService $pawaPay)
    {
        try {
            $pawaPay->ratesMeta(forceRefresh: true);
        } catch (Throwable $exception) {
            return redirect()
                ->route('admin.settings', ['tab' => 'momo'])
                ->withErrors(['rates' => $exception->getMessage()]);
        }

        return redirect()
            ->route('admin.settings', ['tab' => 'momo'])
            ->with('success', 'Taux CurrencyFreaks rafraîchis.');
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
