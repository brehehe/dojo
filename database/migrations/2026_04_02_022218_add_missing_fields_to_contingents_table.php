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
            $table->string('kab_kota')->nullable();
            $table->string('transfer_proof_path')->nullable();
            $table->string('sim_perkemi_confirm')->nullable();
            $table->renameColumn('leader_email', 'email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contingents', function (Blueprint $table) {
            $table->dropColumn(['kab_kota', 'transfer_proof_path', 'sim_perkemi_confirm']);
            $table->renameColumn('email', 'leader_email');
        });
    }
};
