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
        Schema::create('audiobook_sections', function (Blueprint $table) {
            $table->id();
                   $table->foreignId('audiobook_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->unsignedInteger('position');

            $table->string('type')
                ->default('section');

            $table->string('number')
                ->nullable();

            $table->string('title')
                ->nullable();

            $table->longText('text')
                ->nullable();

            $table->longText('narration_text')
                ->nullable();

            $table->unsignedBigInteger('characters')
                ->default(0);

            $table->unsignedBigInteger('words')
                ->default(0);

            $table->unsignedInteger('start_page')
                ->nullable();

            $table->unsignedInteger('end_page')
                ->nullable();

            $table->string('detection_method')
                ->nullable();

            $table->decimal('confidence', 5, 4)
                ->nullable();

            $table->string('status')
                ->default('pending');

            $table->string('audio_path')
                ->nullable();

            $table->unsignedInteger('duration_seconds')
                ->nullable();

            $table->text('error_message')
                ->nullable();

            $table->timestamps();

            $table->unique([
                'audiobook_id',
                'position',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audiobook_sections');
    }
};
