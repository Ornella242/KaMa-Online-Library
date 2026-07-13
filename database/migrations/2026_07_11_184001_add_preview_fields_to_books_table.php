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
        Schema::table('books', function (Blueprint $table) {
            $table->enum('preview_type', ['text', 'pages'])
              ->default('text')
              ->after('long_description');

            $table->unsignedInteger('preview_start_page')
              ->nullable()
              ->after('preview_type');

            $table->unsignedInteger('preview_end_page')
                ->nullable()
                ->after('preview_start_page');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn(['preview_type','preview_start_page','preview_start_page']);
        });
    }
};
