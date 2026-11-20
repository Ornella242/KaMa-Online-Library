<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('publication_fees')) {
            DB::table('publication_fees')->update(['currency' => 'USD']);
        }

        if (Schema::hasTable('orders') && Schema::hasColumn('orders', 'currency')) {
            DB::table('orders')->where('currency', 'XOF')->update(['currency' => 'USD']);
        }

        if (Schema::hasTable('payments') && Schema::hasColumn('payments', 'currency')) {
            DB::table('payments')->where('currency', 'XOF')->update(['currency' => 'USD']);
        }

        if (Schema::hasTable('wallets') && Schema::hasColumn('wallets', 'currency')) {
            DB::table('wallets')->where('currency', 'XOF')->update(['currency' => 'USD']);
        }
    }

    public function down(): void
    {
        // Intentionally left blank: reverting to XOF is not desired.
    }
};
