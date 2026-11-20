<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('audiobook_requests', function (Blueprint $table) {
            $table->foreignId('published_book_id')
                ->nullable()
                ->after('audiobook_id')
                ->constrained('books')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('audiobook_requests', function (Blueprint $table) {
            $table->dropForeign(['published_book_id']);
            $table->dropColumn('published_book_id');
        });
    }
};
