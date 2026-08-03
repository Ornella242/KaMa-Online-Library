<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('withdrawals', function (Blueprint $table) {
            $table->decimal('commission_percent', 5, 2)->default(0)->after('amount');
            $table->decimal('commission_amount', 10, 2)->default(0)->after('commission_percent');
            $table->decimal('net_amount', 10, 2)->default(0)->after('commission_amount');
            $table->json('payout_details')->nullable()->after('account_number');
            $table->text('admin_note')->nullable()->after('payout_details');
            $table->timestamp('processing_at')->nullable()->after('admin_note');
            $table->timestamp('completed_at')->nullable()->after('processing_at');
            $table->timestamp('rejected_at')->nullable()->after('completed_at');
            $table->foreignId('processed_by')->nullable()->after('rejected_at')->constrained('users')->nullOnDelete();
        });

        // Map legacy statuses, then widen the enum.
        DB::table('withdrawals')->where('status', 'pending')->update(['status' => 'initiated']);
        DB::table('withdrawals')->where('status', 'approved')->update(['status' => 'completed']);

        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE withdrawals MODIFY COLUMN status ENUM('initiated','processing','completed','rejected','pending','approved') NOT NULL DEFAULT 'initiated'");
            DB::statement("ALTER TABLE withdrawals MODIFY COLUMN status ENUM('initiated','processing','completed','rejected') NOT NULL DEFAULT 'initiated'");
        } else {
            // SQLite / others: recreate constraint via temporary column if needed — keep string statuses as-is.
            Schema::table('withdrawals', function (Blueprint $table) {
                $table->string('status', 20)->default('initiated')->change();
            });
        }

        DB::table('withdrawals')
            ->where('net_amount', 0)
            ->update([
                'net_amount' => DB::raw('amount'),
            ]);
    }

    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        DB::table('withdrawals')->where('status', 'initiated')->update(['status' => 'pending']);
        DB::table('withdrawals')->where('status', 'processing')->update(['status' => 'pending']);
        DB::table('withdrawals')->where('status', 'completed')->update(['status' => 'approved']);

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE withdrawals MODIFY COLUMN status ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending'");
        }

        Schema::table('withdrawals', function (Blueprint $table) {
            $table->dropConstrainedForeignId('processed_by');
            $table->dropColumn([
                'commission_percent',
                'commission_amount',
                'net_amount',
                'payout_details',
                'admin_note',
                'processing_at',
                'completed_at',
                'rejected_at',
            ]);
        });
    }
};
