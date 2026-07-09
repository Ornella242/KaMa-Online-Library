<?php

namespace App\Http\Controllers\Writer;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Withdrawal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class RevenueController extends Controller
{
    public function index()
    {

        $userId = Auth::id();
        $totalRevenue = Payment::whereHas('book', function($q) use($userId){

                $q->where('user_id', $userId);

            })
            ->where('status', 'success')
            ->where('type', 'purchase')
            ->sum('amount');

        $monthlyRevenue = Payment::whereHas('book', function($q) use($userId){

                $q->where('user_id', $userId);

            })
            ->where('status', 'success')
            ->where('type', 'purchase')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');


        $totalWithdrawn = Withdrawal::query()->where('user_id',$userId)
            ->where('status','approved')
            ->sum('amount');


        $availableBalance = $totalRevenue - $totalWithdrawn;


        $totalSales = Payment::whereHas('book', function($q) use($userId){

                $q->where('user_id', $userId);

            })
            ->where('status', 'success')
            ->where('type', 'purchase')
            ->count();


        $totalReaders = Payment::whereHas('book', function($q) use($userId){

                $q->where('user_id', $userId);

            })
            ->where('status', 'success')
            ->where('type', 'purchase')
            ->distinct('user_id')
            ->count('user_id');


        $pendingAmount = Payment::whereHas('book', function($q) use($userId){

                $q->where('user_id', $userId);

            })
            ->where('status', 'pending')
            ->where('type', 'purchase')
            ->sum('amount');

       $purchasePayments = Payment::with(['book','user'])
            ->whereHas('book', function($query) use ($userId){

                $query->where('user_id',$userId);

            })
            ->where('type','purchase')
            ->where('status','success')
            ->latest()
            ->paginate(10);

            $publicationPayments = Payment::with('book')
                ->whereHas('book', function($query) use ($userId){

                    $query->where('user_id',$userId);

                })
                ->where('type','publication')
                ->latest()
                ->paginate(10);

        return view(
            'writer.revenues',
            compact(
                'totalRevenue',
                'monthlyRevenue',
                'totalSales',
                'totalReaders',
                'pendingAmount',
                'purchasePayments',
                'publicationPayments',
                'availableBalance',
                'totalWithdrawn',
            )
        );

    }

}
