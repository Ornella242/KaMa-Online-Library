<?php

namespace App\Http\Controllers\Reader;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Payment;
use App\Services\PurchaseClaimService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LibraryController extends Controller
{
    public function __construct(private PurchaseClaimService $claims)
    {
    }

    public function index()
    {
        $user = Auth::user();
        $this->claims->claimFor($user);

        $purchases = Payment::query()
            ->with(['book.author', 'book.category', 'book.subcategory'])
            ->where('user_id', $user->id)
            ->where('type', 'purchase')
            ->where('status', 'success')
            ->latest()
            ->get()
            ->unique('book_id')
            ->filter(fn (Payment $payment) => $payment->book !== null)
            ->values();

        $stats = [
            'total' => $purchases->count(),
            'ebooks' => $purchases->filter(fn (Payment $payment) => $payment->book?->type === 'ebook')->count(),
            'audio' => $purchases->filter(fn (Payment $payment) => $payment->book?->type === 'audio')->count(),
            'spent' => $purchases->sum(fn (Payment $payment) => (float) $payment->amount),
        ];

        return view('reader.book', compact('purchases', 'stats'));
    }

    public function download(Book $book)
    {
        abort_unless($this->claims->userOwnsBook(Auth::user(), $book->id), 403);
        abort_unless(filled($book->file_path) && Storage::disk('local')->exists($book->file_path), 404);

        $filename = $book->original_file_name ?: basename($book->file_path);

        return Storage::disk('local')->download($book->file_path, $filename);
    }
}
