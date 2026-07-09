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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('restrict');
            $table->foreignId('subcategory_id')
                   ->constrained()
                   ->onDelete('restrict');
            $table->string('title');
            $table->text('short_description');
            $table->longText('long_description');
            $table->enum('type', ['ebook', 'audio']);
            $table->decimal('price', 10, 2);
            $table->integer('pages');
            $table->string('language');
            $table->enum('status', [
                    'draft',
                    'pending_payment',
                    'published',
                    'unpublished'
                ])->default('draft');
            $table->year('publication_year');
            $table->string('cover_image');
            $table->string('file_path');
            $table->enum('file_type', ['pdf', 'mp3']);
            $table->bigInteger('file_size')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
