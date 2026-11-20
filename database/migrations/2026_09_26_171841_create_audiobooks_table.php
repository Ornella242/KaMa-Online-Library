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
        Schema::create('audiobooks', function (Blueprint $table) {
            $table->id();

            $table->foreignId('book_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('status')
                ->default('draft');

            $table->string('voice_id')->nullable();

            $table->string('model')
                ->default('eleven_v3');

            $table->unsignedBigInteger('total_characters')
                ->default(0);

            $table->unsignedBigInteger('generated_characters')
                ->default(0);

            $table->unsignedInteger('total_chunks')
                ->default(0);

            $table->unsignedInteger('completed_chunks')
                ->default(0);

            $table->decimal('estimated_cost', 10, 4)
                ->default(0);

            $table->decimal('actual_cost', 10, 4)
                ->default(0);

            $table->string('final_audio_path')
                ->nullable();

            $table->text('error_message')
                ->nullable();

            $table->timestamp('started_at')
                ->nullable();

            $table->timestamp('completed_at')
                ->nullable();

            $table->timestamps();

            $table->unique('book_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audiobooks');
    }
};
