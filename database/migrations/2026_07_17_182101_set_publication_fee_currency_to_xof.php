<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('publication_fees')->update(['currency' => 'XOF']);
    }

    public function down(): void
    {
        DB::table('publication_fees')->update(['currency' => 'USD']);
    }
};
