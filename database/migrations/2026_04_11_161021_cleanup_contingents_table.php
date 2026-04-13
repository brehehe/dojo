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
        Schema::table('contingents', function (Blueprint $table) {
            $table->dropColumn([
                'total_cost',
                'status',
                'referral_code',
                'payment_method',
                'unique_code',
                'final_amount',
                'transfer_proof_path',
                'sim_perkemi_confirm',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contingents', function (Blueprint $table) {
            $table->decimal('total_cost', 15, 2)->default(0);
            $table->string('status')->default('pending');
            $table->string('referral_code')->nullable();
            $table->string('payment_method')->nullable();
            $table->integer('unique_code')->default(0);
            $table->decimal('final_amount', 15, 2)->default(0);
            $table->string('transfer_proof_path')->nullable();
            $table->string('sim_perkemi_confirm')->default('Ya');
        });
    }
};
