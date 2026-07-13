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
        Schema::create('advertisements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')
          ->constrained()
          ->cascadeOnDelete();

        // celui qui paie la publicité
        $table->foreignId('user_id')
            ->constrained()
            ->cascadeOnDelete();

        $table->date('start_date');
        $table->date('end_date');

        $table->enum('status', [
            'pending',
            'active',
            'expired',
            'rejected'
        ])->default('pending');

        $table->decimal('amount',10,2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advertisements');
    }
};
