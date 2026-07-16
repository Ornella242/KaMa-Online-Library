<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            ALTER TABLE books 
            MODIFY status ENUM(
                'draft',
                'waiting_review',
                'under_review',
                'published',
                'rejected',
                'revision_required'
            ) DEFAULT 'draft'
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("
        ALTER TABLE books 
        MODIFY status ENUM(
            'draft',
            'waiting_review',
            'under_review',
            'published',
            'rejected'
        ) DEFAULT 'draft'
    ");
    }
};
