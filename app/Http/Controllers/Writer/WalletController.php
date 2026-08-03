<?php

namespace App\Http\Controllers\Writer;

use App\Http\Controllers\Controller;
use App\Services\WalletService;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    public function index(WalletService $walletService)
    {
        $user = Auth::user();
        $wallet = $walletService->ensureWallet($user);

        $transactions = $wallet->transactions()
            ->with(['payment.book', 'payment.user'])
            ->latest()
            ->paginate(15);

        $totalCredited = (float) $wallet->transactions()
            ->where('type', 'sale')
            ->sum('amount');

        $totalWithdrawn = (float) $wallet->transactions()
            ->where('type', 'withdrawal')
            ->sum('amount');

        $salesThisMonth = (float) $wallet->transactions()
            ->where('type', 'sale')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');

        return view('writer.wallet', compact(
            'wallet',
            'transactions',
            'totalCredited',
            'totalWithdrawn',
            'salesThisMonth',
        ));
    }
}
