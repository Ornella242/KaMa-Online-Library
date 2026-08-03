<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Withdrawal;
use App\Services\WithdrawalService;
use Illuminate\Http\Request;

class WithdrawalController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->string('status')->toString();

        $withdrawals = Withdrawal::query()
            ->with(['user', 'processor'])
            ->when(
                $status !== '' && in_array($status, [
                    Withdrawal::STATUS_INITIATED,
                    Withdrawal::STATUS_PROCESSING,
                    Withdrawal::STATUS_COMPLETED,
                    Withdrawal::STATUS_REJECTED,
                ], true),
                fn ($q) => $q->where('status', $status)
            )
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $stats = [
            'initiated' => Withdrawal::query()->where('status', Withdrawal::STATUS_INITIATED)->count(),
            'processing' => Withdrawal::query()->where('status', Withdrawal::STATUS_PROCESSING)->count(),
            'completed' => Withdrawal::query()->where('status', Withdrawal::STATUS_COMPLETED)->count(),
            'rejected' => Withdrawal::query()->where('status', Withdrawal::STATUS_REJECTED)->count(),
            'pending_amount' => (float) Withdrawal::query()
                ->whereIn('status', [Withdrawal::STATUS_INITIATED, Withdrawal::STATUS_PROCESSING])
                ->sum('net_amount'),
            'commission_earned' => (float) Withdrawal::query()
                ->where('status', Withdrawal::STATUS_COMPLETED)
                ->sum('commission_amount'),
        ];

        return view('admin.withdrawals.index', compact('withdrawals', 'stats', 'status'));
    }

    public function show(Withdrawal $withdrawal)
    {
        $withdrawal->load(['user', 'processor']);

        return view('admin.withdrawals.show', compact('withdrawal'));
    }

    public function process(Withdrawal $withdrawal, WithdrawalService $withdrawalService)
    {
        $withdrawalService->markProcessing($withdrawal);

        return back()->with('success', 'Demande passée en cours de traitement.');
    }

    public function complete(Request $request, Withdrawal $withdrawal, WithdrawalService $withdrawalService)
    {
        $validated = $request->validate([
            'admin_note' => ['nullable', 'string', 'max:1000'],
        ]);

        $withdrawalService->markCompleted($withdrawal, $validated['admin_note'] ?? null);

        return back()->with('success', 'Retrait marqué comme terminé. Le versement net a été effectué hors plateforme.');
    }

    public function reject(Request $request, Withdrawal $withdrawal, WithdrawalService $withdrawalService)
    {
        $validated = $request->validate([
            'admin_note' => ['nullable', 'string', 'max:1000'],
        ]);

        $withdrawalService->reject($withdrawal, $validated['admin_note'] ?? null);

        return back()->with('success', 'Demande refusée. Le montant a été recrédité sur le portefeuille de l’auteur.');
    }
}
