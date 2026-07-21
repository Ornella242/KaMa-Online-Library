<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('publication_fees', function (Blueprint $table) {
            $table->id();
            $table->enum('book_type', ['ebook', 'audio'])->unique();
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('USD');
            $table->timestamps();
        });

        DB::table('publication_fees')->insert([
            [
                'book_type' => 'ebook',
                'amount' => 10,
                'currency' => 'USD',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'book_type' => 'audio',
                'amount' => 15,
                'currency' => 'USD',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('publication_fees');
    }
};
