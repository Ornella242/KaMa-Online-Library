<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
      public function up(): void
    {
        if (Schema::hasTable('publication_fees')) {
            DB::table('publication_fees')->update(['currency' => 'EUR']);
        }

        if (Schema::hasTable('orders') && Schema::hasColumn('orders', 'currency')) {
            DB::table('orders')->where('currency', 'XOF')->update(['currency' => 'EUR']);
        }

        if (Schema::hasTable('payments') && Schema::hasColumn('payments', 'currency')) {
            DB::table('payments')->where('currency', 'XOF')->update(['currency' => 'EUR']);
        }

        if (Schema::hasTable('wallets') && Schema::hasColumn('wallets', 'currency')) {
            DB::table('wallets')->where('currency', 'XOF')->update(['currency' => 'EUR']);
        }
    }

};
