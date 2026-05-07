<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_status')->default('pending')->after('status');
            $table->timestamp('payment_reviewed_at')->nullable()->after('payment_status');
            $table->text('payment_notes')->nullable()->after('payment_reviewed_at');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'payment_status',
                'payment_reviewed_at',
                'payment_notes',
            ]);
        });
    }
};
