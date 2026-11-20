<?php

namespace App\Console\Commands;

use App\Models\Book;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateBookSlugs extends Command
{
    protected $signature = 'books:generate-slugs';

    protected $description = 'Génère des slugs uniques pour les livres qui n’en ont pas';

    public function handle(): int
    {
        $count = 0;

        Book::query()
            ->where(function ($query) {
                $query->whereNull('slug')
                    ->orWhere('slug', '');
            })
            ->orderBy('id')
            ->chunkById(100, function ($books) use (&$count) {
                foreach ($books as $book) {
                    $baseSlug = Str::slug($book->title);

                    if ($baseSlug === '') {
                        $baseSlug = 'livre';
                    }

                    $slug = $baseSlug;
                    $suffix = 2;

                    while (
                        Book::where('slug', $slug)
                            ->where('id', '!=', $book->id)
                            ->exists()
                    ) {
                        $slug = $baseSlug . '-' . $suffix;
                        $suffix++;
                    }

                    $book->slug = $slug;
                    $book->saveQuietly();

                    $count++;
                }
            });

        $this->info("Terminé : {$count} slug(s) généré(s).");

        return self::SUCCESS;
    }
}
