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
        // 1. Add registration_id column
        Schema::table('athletes', function (Blueprint $table) {
            $table->foreignId('registration_id')->nullable()->after('contingent_id')->constrained()->onDelete('cascade');
        });

        Schema::table('officials', function (Blueprint $table) {
            $table->foreignId('registration_id')->nullable()->after('contingent_id')->constrained()->onDelete('cascade');
        });

        // 2. Link existing data
        // We find the registration for each contingent.
        // Note: For existing data, there's only 1 registration per contingent.
        $registrations = DB::table('registrations')->get();
        foreach ($registrations as $reg) {
            DB::table('athletes')
                ->where('contingent_id', $reg->contingent_id)
                ->update(['registration_id' => $reg->id]);

            DB::table('officials')
                ->where('contingent_id', $reg->contingent_id)
                ->update(['registration_id' => $reg->id]);
        }

        // 3. Cleanup: Remove old contingent_id columns
        Schema::table('athletes', function (Blueprint $table) {
            $table->dropForeign(['contingent_id']);
            $table->dropColumn('contingent_id');
            $table->foreignId('registration_id')->nullable(false)->change();
        });

        Schema::table('officials', function (Blueprint $table) {
            // $table->dropForeign(['contingent_id']);
            // $table->dropColumn('contingent_id');
            $table->foreignId('registration_id')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('athletes', function (Blueprint $table) {
            $table->foreignId('contingent_id')->nullable()->constrained();
        });
        Schema::table('officials', function (Blueprint $table) {
            $table->foreignId('contingent_id')->nullable()->constrained();
        });

        // Re-link back if needed... (simplified)

        Schema::table('athletes', function (Blueprint $table) {
            $table->dropForeign(['registration_id']);
            $table->dropColumn('registration_id');
        });
        Schema::table('officials', function (Blueprint $table) {
            $table->dropForeign(['registration_id']);
            $table->dropColumn('registration_id');
        });
    }
};
