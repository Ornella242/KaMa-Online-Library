<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE wallet_transactions MODIFY COLUMN type ENUM('sale','withdrawal','refund') NOT NULL");
        }

        Schema::table('withdrawals', function (Blueprint $table) {
            $table->string('payout_reference')->nullable()->after('admin_note');
        });
    }

    public function down(): void
    {
        Schema::table('withdrawals', function (Blueprint $table) {
            $table->dropColumn('payout_reference');
        });

        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE wallet_transactions MODIFY COLUMN type ENUM('sale','withdrawal') NOT NULL");
        }
    }
};
