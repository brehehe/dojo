<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('randori_match_results', function (Blueprint $table) {
            // Replace integer bracket_node_index with a string key like 'ub_0_2', 'lb_1_0', 'gf'
            $table->string('bracket_node')->nullable()->after('bracket_node_index');
            // Track which bracket section: ub, lb, gf
            $table->string('bracket_section')->default('ub')->after('bracket_node');
        });

        // Migrate existing data
        DB::table('randori_match_results')->whereNotNull('bracket_node_index')
            ->get()
            ->each(function ($row) {
                DB::table('randori_match_results')
                    ->where('id', $row->id)
                    ->update([
                        'bracket_node' => 'ub_'.$row->bracket_node_index,
                        'bracket_section' => 'ub',
                    ]);
            });
    }

    public function down(): void
    {
        Schema::table('randori_match_results', function (Blueprint $table) {
            $table->dropColumn(['bracket_node', 'bracket_section']);
        });
    }
};
