<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('embu_scores', function (Blueprint $table) {
            // Support tiebreak rounds (0 = normal, 1 = 1st tiebreak, 2 = 2nd, etc.)
            $table->tinyInteger('tiebreak_round')->default(0)->after('rank');
            // Denda (penalty deduction from nilai awal)
            $table->decimal('denda', 8, 2)->default(0)->after('tiebreak_round');
            // Nilai akhir = total_score - denda
            $table->decimal('nilai_akhir', 12, 2)->default(0)->after('denda');
            // Round label for display (Penyisihan, Final, etc.)
            $table->string('round_label')->default('Penyisihan')->after('nilai_akhir');
        });
    }

    public function down(): void
    {
        Schema::table('embu_scores', function (Blueprint $table) {
            $table->dropColumn(['tiebreak_round', 'denda', 'nilai_akhir', 'round_label']);
        });
    }
};
