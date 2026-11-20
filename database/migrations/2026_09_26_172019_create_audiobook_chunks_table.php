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
        Schema::create('audiobook_chunks', function (Blueprint $table) {
            $table->id();
             $table->foreignId('audiobook_section_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->unsignedInteger('position');

            $table->longText('text');

            $table->unsignedInteger('characters')
                ->default(0);

            $table->unsignedInteger('character_cost')
                ->default(0);

            $table->string('status')
                ->default('pending');

            $table->string('audio_path')
                ->nullable();

            $table->string('request_id')
                ->nullable();

            $table->string('trace_id')
                ->nullable();

            $table->unsignedInteger('duration_seconds')
                ->nullable();

            $table->text('error_message')
                ->nullable();

            $table->timestamp('started_at')
                ->nullable();

            $table->timestamp('completed_at')
                ->nullable();

            $table->timestamps();

            $table->unique([
                'audiobook_section_id',
                'position',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audiobook_chunks');
    }
};
