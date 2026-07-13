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
        Schema::create('book_sponsorships', function (Blueprint $table) {
            $table->id();
            // Livre sponsorisé
            $table->foreignId('book_id')
                ->constrained()
                ->cascadeOnDelete();

            // Auteur ayant effectué le sponsoring
            $table->foreignId('writer_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Offre choisie
            $table->foreignId('sponsorship_plan_id')
                ->constrained()
                ->cascadeOnDelete();

            // Montant payé au moment de l'achat
            $table->decimal('amount', 10, 2);

            // Référence du paiement
            $table->string('transaction_reference')
                ->nullable();

            // Statut
            $table->enum('status', [
                'pending',
                'paid',
                'failed',
                'cancelled',
                'expired'
            ])->default('pending');

            // Début de la campagne
            $table->timestamp('starts_at')
                ->nullable();

            // Fin de la campagne
            $table->timestamp('ends_at')
                ->nullable();

            // Date du paiement
            $table->timestamp('paid_at')
                ->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_sponsorships');
    }
};
