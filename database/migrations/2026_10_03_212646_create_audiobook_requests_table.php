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
        Schema::create('audiobook_requests', function (Blueprint $table) {
            $table->id();
            // Ebook source
            $table->foreignId('book_id')
                ->constrained('books')
                ->cascadeOnDelete();

            // Auteur qui fait la demande
            $table->foreignId('author_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Voice ID ElevenLabs sélectionné par l'auteur
            $table->string('voice_id');

            $table->unsignedInteger('characters')->default(0);
            $table->unsignedInteger('words')->default(0);

            // Coût estimé de génération ElevenLabs
            $table->decimal('elevenlabs_cost', 10, 4)->default(0);

            // Frais KaMa = 20% du prix du ebook
            $table->decimal('kama_fee', 10, 2)->default(0);

            $table->decimal('total_amount', 10, 2)->default(0);


            $table->string('status')
                ->default('pending_payment');

            $table->foreignId('payment_id')
                ->nullable()
                ->constrained('payments')
                ->nullOnDelete();

            $table->foreignId('audiobook_id')
                ->nullable()
                ->constrained('audiobooks')
                ->nullOnDelete();

            $table->index('status');
            $table->index(['book_id', 'author_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audiobook_requests');
    }
};
