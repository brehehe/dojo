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
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contingent_id')->constrained()->onDelete('cascade');
            $table->decimal('total_cost', 15, 2)->default(0);
            $table->decimal('final_amount', 15, 2)->default(0);
            $table->integer('unique_code')->default(0);
            $table->string('payment_method')->nullable();
            $table->string('referral_code')->nullable();
            $table->string('status')->default('pending');
            $table->string('transfer_proof_path')->nullable();
            $table->string('sim_perkemi_confirm')->default('Ya');
            $table->timestamps();
        });

        // Move existing transactional data from contingents to registrations
        $contingents = DB::table('contingents')->get();

        foreach ($contingents as $contingent) {
            DB::table('registrations')->insert([
                'contingent_id' => $contingent->id,
                'total_cost' => $contingent->total_cost ?? 0,
                'final_amount' => $contingent->final_amount ?? 0,
                'unique_code' => $contingent->unique_code ?? 0,
                'payment_method' => $contingent->payment_method,
                'referral_code' => $contingent->referral_code,
                'status' => $contingent->status ?? 'pending',
                'transfer_proof_path' => $contingent->transfer_proof_path,
                'sim_perkemi_confirm' => $contingent->sim_perkemi_confirm ?? 'Ya',
                'created_at' => $contingent->created_at,
                'updated_at' => $contingent->updated_at,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
