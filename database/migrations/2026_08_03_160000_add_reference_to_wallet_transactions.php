<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->string('reference')->nullable()->after('description');
            $table->foreignId('payment_id')
                ->nullable()
                ->after('reference')
                ->constrained('payments')
                ->nullOnDelete();

            $table->unique('reference');
        });
    }

    public function down(): void
    {
        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->dropUnique(['reference']);
            $table->dropConstrainedForeignId('payment_id');
            $table->dropColumn('reference');
        });
    }
};
