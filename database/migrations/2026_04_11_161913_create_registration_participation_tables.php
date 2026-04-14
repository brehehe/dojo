<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Create registration_athlete pivot
        Schema::create('registration_athlete', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->constrained()->onDelete('cascade');
            $table->foreignId('athlete_id')->constrained()->onDelete('cascade');
            $table->decimal('weight', 8, 2)->nullable();
            $table->string('kyu')->nullable();
            $table->string('age_group')->nullable();
            $table->string('rank')->nullable();
            $table->string('match_type')->nullable();
            $table->string('dojo_origin')->nullable();
            $table->string('city')->nullable();
            $table->integer('age')->nullable();
            $table->timestamps();
        });

        // 2. Create registration_official pivot
        Schema::create('registration_official', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->constrained()->onDelete('cascade');
            $table->foreignId('official_id')->constrained()->onDelete('cascade');
            $table->string('role')->nullable();
            $table->timestamps();
        });

        // 3. Update athlete_category to include registration context
        Schema::table('athlete_category', function (Blueprint $table) {
            $table->foreignId('registration_id')->nullable()->after('athlete_id')->constrained()->onDelete('cascade');
        });

        // 4. Data Migration: Move existing athlete data to pivot
        $athletes = DB::table('athletes')->get();
        foreach ($athletes as $athlete) {
            DB::table('registration_athlete')->insert([
                'registration_id' => $athlete->registration_id,
                'athlete_id' => $athlete->id,
                'weight' => $athlete->weight,
                'kyu' => $athlete->kyu,
                'age_group' => $athlete->age_group,
                'rank' => $athlete->rank,
                'match_type' => $athlete->match_type,
                'dojo_origin' => $athlete->dojo_origin,
                'city' => $athlete->city,
                'age' => $athlete->age,
                'created_at' => $athlete->created_at,
                'updated_at' => $athlete->updated_at,
            ]);

            // Link existing athlete_category to the registration
            DB::table('athlete_category')
                ->where('athlete_id', $athlete->id)
                ->update(['registration_id' => $athlete->registration_id]);
        }

        // 5. Data Migration: Move existing official data to pivot
        $officials = DB::table('officials')->get();
        foreach ($officials as $official) {
            DB::table('registration_official')->insert([
                'registration_id' => $official->registration_id,
                'official_id' => $official->id,
                'role' => $official->role,
                'created_at' => $official->created_at,
                'updated_at' => $official->updated_at,
            ]);
        }

        // 6. Finalize: Remove registration_id from master tables
        Schema::table('athletes', function (Blueprint $table) {
            $table->dropForeign(['registration_id']);
            $table->dropColumn([
                'registration_id',
                'weight',
                'kyu',
                'age_group',
                'rank',
                'match_type',
                'dojo_origin',
                'city',
                'age',
            ]);
        });

        Schema::table('officials', function (Blueprint $table) {
            $table->dropForeign(['registration_id']);
            $table->dropColumn(['registration_id']);
        });

        Schema::table('athlete_category', function (Blueprint $table) {
            $table->foreignId('registration_id')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('athlete_category', function (Blueprint $table) {
            $table->dropForeign(['registration_id']);
            $table->dropColumn('registration_id');
        });

        Schema::dropIfExists('registration_official');
        Schema::dropIfExists('registration_athlete');

        // Logic to restore athletes/officials tables would be complex...
    }
};
