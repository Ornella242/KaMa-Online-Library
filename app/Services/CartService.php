<?php

namespace App\Services;

use App\Models\Book;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;

class CartService
{
    private const SESSION_KEY = 'kama_cart';

    public function items(): array
    {
        return Session::get(self::SESSION_KEY, []);
    }

    public function count(): int
    {
        return count($this->items());
    }

    public function has(int $bookId): bool
    {
        return isset($this->items()[$bookId]);
    }

    public function add(Book $book): void
    {
        abort_unless($book->status === Book::STATUS_PUBLISHED, 404);

        $cart = $this->items();

        $cart[$book->id] = [
            'book_id' => $book->id,
            'title' => $book->title,
            'price' => (float) $book->price,
            'type' => $book->type,
            'cover_image' => $book->cover_image,
            'author' => trim(($book->author?->firstname ?? '') . ' ' . ($book->author?->lastname ?? '')),
        ];

        Session::put(self::SESSION_KEY, $cart);
    }

    public function remove(int $bookId): void
    {
        $cart = $this->items();
        unset($cart[$bookId]);
        Session::put(self::SESSION_KEY, $cart);
    }

    public function clear(): void
    {
        Session::forget(self::SESSION_KEY);
    }

    public function total(): float
    {
        return collect($this->items())->sum('price');
    }

    public function books(): Collection
    {
        $ids = array_keys($this->items());

        if ($ids === []) {
            return collect();
        }

        return Book::query()
            ->with('author')
            ->published()
            ->whereIn('id', $ids)
            ->get()
            ->keyBy('id');
    }
}
