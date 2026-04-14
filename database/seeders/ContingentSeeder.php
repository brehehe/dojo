<?php

use App\Models\Athlete;
use App\Models\Category;
use App\Models\Contingent;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class ContingentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // For Postgres FK constraint checks
        DB::statement('SET session_replication_role = \'replica\';');
        Contingent::truncate();
        Registration::truncate();
        Athlete::truncate();
        DB::table('athlete_contingent')->truncate();
        DB::table('athlete_contingent_histories')->truncate();
        DB::table('registration_athlete')->truncate();
        DB::table('athlete_category')->truncate();

        // Remove existing Sura/Baya users if they exist to prevent duplication on re-run
        User::whereIn('email', ['sura@example.com', 'baya@example.com'])->delete();
        DB::statement('SET session_replication_role = \'origin\';');

        $contingents = [
            [
                'name' => 'Sura',
                'email' => 'sura@example.com',
                'password' => 'password',
                'contingent_name' => 'Kontingen Sura',
                'contingent_city' => 'Surabaya',
                'leader_name' => 'Ketua Sura',
                'leader_phone' => '081234567890',
                'address' => 'Jl. Sura No. 1',
            ],
            [
                'name' => 'Baya',
                'email' => 'baya@example.com',
                'password' => 'password',
                'contingent_name' => 'Kontingen Baya',
                'contingent_city' => 'Surabaya',
                'leader_name' => 'Ketua Baya',
                'leader_phone' => '081234567891',
                'address' => 'Jl. Baya No. 2',
            ],
        ];

    foreach ($contingents as $data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            // Assign 'Contingent' role specifically for member registration
            $user->assignRole('Contingent');

            $contingent = Contingent::create([
                'user_id' => $user->id,
                'name' => $data['contingent_name'],
                'kab_kota' => $data['contingent_city'],
                'leader_name' => $data['leader_name'],
                'leader_phone' => $data['leader_phone'],
                'email' => $user->email,
                'address' => $data['address'],
            ]);

            // Create a registration for the contingent
            $registration = Registration::create([
                'contingent_id' => $contingent->id,
                'total_cost' => 0,
                'final_amount' => 0,
                'unique_code' => rand(100, 999),
                'status' => 'pending',
                'sim_perkemi_confirm' => 'Ya',
            ]);

            // Seed athletes for each contingent
            $this->seedAthletesForContingent($contingent, $registration);
        }
    }

    private function seedAthletesForContingent(Contingent $contingent, Registration $registration): void
    {
        $contingentType = str_contains(strtolower($contingent->name), 'sura') ? 'Sura' : 'Baya';
        
        $athletesData = [
            'Sura' => [
                [
                    'nik' => '3578010101000001',
                    'name' => 'Budi Santoso',
                    'gender' => 'Male',
                    'birth_date' => '2008-05-15',
                    'birth_place' => 'Surabaya',
                    'blood_type' => 'O',
                    'address' => 'Jl. Gubeng No. 10',
                    'phone' => '081234567890',
                    'bpjs_status' => 'Aktif',
                    'bpjs_number' => '0001234567891',
                    'weight' => 55.5,
                    'kyu' => 'Kyu 1',
                    'dojo_origin' => 'Sura Dojo Center',
                    'city' => 'Surabaya',
                    'age_group' => 'Remaja B',
                    'match_type' => 'Kempo',
                    'rank' => 'Kyu 1',
                ],
                [
                    'nik' => '3578010101000002',
                    'name' => 'Siti Aminah',
                    'gender' => 'Female',
                    'birth_date' => '2010-08-20',
                    'birth_place' => 'Surabaya',
                    'blood_type' => 'A',
                    'address' => 'Jl. Darmo No. 5',
                    'phone' => '081234567892',
                    'bpjs_status' => 'Aktif',
                    'bpjs_number' => '0001234567892',
                    'weight' => 48.2,
                    'kyu' => 'Kyu 2',
                    'dojo_origin' => 'Surabaya Timur Dojo',
                    'city' => 'Surabaya',
                    'age_group' => 'Remaja A',
                    'match_type' => 'Kempo',
                    'rank' => 'Kyu 2',
                ],
                [
                    'nik' => '3578010101000003',
                    'name' => 'Andi Wijaya',
                    'gender' => 'Male',
                    'birth_date' => '2005-03-10',
                    'birth_place' => 'Sidoarjo',
                    'blood_type' => 'B',
                    'address' => 'Jl. Ahmad Yani No. 100',
                    'phone' => '081234567893',
                    'bpjs_status' => 'Tidak Aktif',
                    'bpjs_number' => '0001234567893',
                    'weight' => 65.0,
                    'kyu' => 'Kyu 3',
                    'dojo_origin' => 'Sura Dojo Academy',
                    'city' => 'Surabaya',
                    'age_group' => 'Dewasa A',
                    'match_type' => 'Kempo',
                    'rank' => 'Kyu 3',
                ],
            ],
            'Baya' => [
                [
                    'nik' => '3578010101000004',
                    'name' => 'Bambang Heru',
                    'gender' => 'Male',
                    'birth_date' => '2007-11-12',
                    'birth_place' => 'Surabaya',
                    'blood_type' => 'AB',
                    'address' => 'Jl. Kenjeran No. 20',
                    'phone' => '081234567894',
                    'bpjs_status' => 'Aktif',
                    'bpjs_number' => '0001234567894',
                    'weight' => 60.0,
                    'kyu' => 'Kyu 1',
                    'dojo_origin' => 'Baya Martial Arts',
                    'city' => 'Surabaya',
                    'age_group' => 'Remaja B',
                    'match_type' => 'Kempo',
                    'rank' => 'Kyu 1',
                ],
                [
                    'nik' => '3578010101000005',
                    'name' => 'Lilis Suryani',
                    'gender' => 'Female',
                    'birth_date' => '2009-01-25',
                    'birth_place' => 'Surabaya',
                    'blood_type' => 'O',
                    'address' => 'Jl. Diponegoro No. 15',
                    'phone' => '081234567895',
                    'bpjs_status' => 'Aktif',
                    'bpjs_number' => '0001234567895',
                    'weight' => 52.5,
                    'kyu' => 'Kyu 2',
                    'dojo_origin' => 'Baya Women Dojo',
                    'city' => 'Surabaya',
                    'age_group' => 'Remaja B',
                    'match_type' => 'Kempo',
                    'rank' => 'Kyu 2',
                ],
            ],
        ];

        $currentAthletes = $athletesData[$contingentType] ?? [];

        foreach ($currentAthletes as $athleteData) {
            $masterData = [
                'nik' => $athleteData['nik'],
                'name' => $athleteData['name'],
                'gender' => $athleteData['gender'],
                'birth_date' => $athleteData['birth_date'],
                'birth_place' => $athleteData['birth_place'] ?? null,
                'blood_type' => $athleteData['blood_type'] ?? null,
                'address' => $athleteData['address'] ?? null,
                'phone' => $athleteData['phone'] ?? null,
                'bpjs_number' => $athleteData['bpjs_number'],
                'bpjs_status' => $athleteData['bpjs_status'],
            ];

            $pivotData = [
                'weight' => $athleteData['weight'],
                'kyu' => $athleteData['kyu'],
                'dojo_origin' => $athleteData['dojo_origin'],
                'city' => $athleteData['city'],
                'age_group' => $athleteData['age_group'],
                'match_type' => $athleteData['match_type'],
                'rank' => $athleteData['rank'],
                'age' => Carbon::parse($athleteData['birth_date'])->age,
            ];

            $athlete = Athlete::create($masterData);

            // Initial membership
            $athlete->contingents()->attach([
                $contingent->id => ['is_primary' => true, 'joined_at' => now()]
            ]);

            // Record initial history
            $athlete->contingentHistories()->create([
                'contingent_id' => $contingent->id,
                'moved_at' => now(),
                'notes' => 'Kontingen pendaftaran awal.',
            ]);

            // Link to registration for tournament
            $athlete->registrations()->attach($registration->id, $pivotData);

            // Sync with some matching categories
            $categories = Category::where('gender', $athleteData['gender'])
                ->where('age_group', $athleteData['age_group'])
                ->limit(2)
                ->get();

            if ($categories->isNotEmpty()) {
                $athlete->categories()->syncWithPivotValues($categories->pluck('id')->toArray(), ['registration_id' => $registration->id]);
            }
        }
    }
}
