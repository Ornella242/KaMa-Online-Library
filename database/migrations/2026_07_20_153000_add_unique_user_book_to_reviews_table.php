<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Keep one review per user/book if duplicates exist
        $duplicates = DB::table('reviews')
            ->select('book_id', 'user_id', DB::raw('MAX(id) as keep_id'))
            ->groupBy('book_id', 'user_id')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        foreach ($duplicates as $duplicate) {
            DB::table('reviews')
                ->where('book_id', $duplicate->book_id)
                ->where('user_id', $duplicate->user_id)
                ->where('id', '!=', $duplicate->keep_id)
                ->delete();
        }

        Schema::table('reviews', function (Blueprint $table) {
            $table->unique(['book_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropUnique(['book_id', 'user_id']);
        });
    }
};
