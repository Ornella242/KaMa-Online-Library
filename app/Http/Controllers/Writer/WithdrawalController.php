<?php

namespace App\Http\Controllers\Writer;

use App\Http\Controllers\Controller;
use App\Models\Withdrawal;
use App\Services\WalletService;
use App\Services\WithdrawalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WithdrawalController extends Controller
{
    public function create(WalletService $walletService, WithdrawalService $withdrawalService)
    {
        $wallet = $walletService->ensureWallet(Auth::user());
        $methods = Withdrawal::paymentMethods();
        $commissionPercent = $withdrawalService->commissionPercent();
        $minimumAmount = $withdrawalService->minimumAmount();

        $openWithdrawal = Withdrawal::query()
            ->where('user_id', Auth::id())
            ->whereIn('status', [Withdrawal::STATUS_INITIATED, Withdrawal::STATUS_PROCESSING])
            ->latest()
            ->first();

        return view('writer.withdrawals.create', compact(
            'wallet',
            'methods',
            'commissionPercent',
            'minimumAmount',
            'openWithdrawal',
        ));
    }

    public function store(Request $request, WithdrawalService $withdrawalService)
    {
        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01'],
            'payment_method' => ['required', 'string', 'in:'.implode(',', array_keys(Withdrawal::paymentMethods()))],
            'payout_details' => ['required', 'array'],
        ]);

        $details = collect($validated['payout_details'])
            ->map(fn ($value) => is_string($value) ? trim($value) : $value)
            ->all();

        $withdrawal = $withdrawalService->request(
            Auth::user(),
            (float) $validated['amount'],
            $validated['payment_method'],
            $details
        );

        return redirect()
            ->route('writer.wallet')
            ->with('success', 'Demande de retrait #'.$withdrawal->id.' initiée. Montant net à recevoir : $'
                .number_format((float) $withdrawal->net_amount, 2, '.', ','));
    }

    public function index()
    {
        $withdrawals = Withdrawal::query()
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(15);

        return view('writer.withdrawals.index', compact('withdrawals'));
    }
}
