<?php

namespace Database\Seeders;

use App\Models\Athlete;
use App\Models\Category;
use App\Models\Contingent;
use App\Models\Registration;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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
                    'nik' => '3578156002050003',
                    'name' => 'Cindy Febriyanti',
                    'gender' => 'Female',
                    'birth_date' => '2005-02-20',
                    'birth_place' => 'Surabaya',
                    'blood_type' => 'O',
                    'address' => 'Jl. Tambak Wedi Baru Utara 18B/28',
                    'phone' => '081234567890',
                    'bpjs_status' => 'Aktif',
                    'bpjs_number' => '0001234567891',
                    'weight' => 50.0,
                    'kyu' => 'I Kyu',
                    'dojo_origin' => 'Sura Dojo Center',
                    'city' => 'Surabaya',
                    'match_type' => 'Kempo',
                    'rank' => 'I Kyu',
                ],
                [
                    'nik' => '3578120111040001',
                    'name' => 'Ramadhani Arta Pradipta',
                    'gender' => 'Male',
                    'birth_date' => '2004-11-04',
                    'birth_place' => 'Surabaya',
                    'blood_type' => 'A',
                    'address' => 'Teluk Aru Utara No. 65A',
                    'phone' => '081234567891',
                    'bpjs_status' => 'Aktif',
                    'bpjs_number' => '0001234567892',
                    'weight' => 60.0,
                    'kyu' => 'II Kyu',
                    'dojo_origin' => 'Sura Dojo Academy',
                    'city' => 'Surabaya',
                    'match_type' => 'Kempo',
                    'rank' => 'II Kyu',
                ],
                [
                    'nik' => '3578124902060001',
                    'name' => 'Bilqis Ammardivia Ghinannafsi',
                    'gender' => 'Female',
                    'birth_date' => '2006-02-09',
                    'birth_place' => 'Surabaya',
                    'blood_type' => 'B',
                    'address' => 'Jl. Teluk Penanjung No. 37',
                    'phone' => '081234567892',
                    'bpjs_status' => 'Aktif',
                    'bpjs_number' => '0001234567893',
                    'weight' => 52.0,
                    'kyu' => 'II Kyu',
                    'dojo_origin' => 'Sura Dojo Center',
                    'city' => 'Surabaya',
                    'match_type' => 'Kempo',
                    'rank' => 'II Kyu',
                ],
                [
                    'nik' => '3578124909100003',
                    'name' => 'Bilvina Aqila Saumifathiyah',
                    'gender' => 'Female',
                    'birth_date' => '2010-09-09',
                    'birth_place' => 'Surabaya',
                    'blood_type' => 'AB',
                    'address' => 'Jl. Teluk Penanjung No. 37',
                    'phone' => '081234567893',
                    'bpjs_status' => 'Aktif',
                    'bpjs_number' => '0001234567894',
                    'weight' => 45.0,
                    'kyu' => 'II Kyu',
                    'dojo_origin' => 'Sura Dojo Academy',
                    'city' => 'Surabaya',
                    'match_type' => 'Kempo',
                    'rank' => 'II Kyu',
                ],
                [
                    'nik' => '3578120612070001',
                    'name' => 'Muhammad Nurrahman Bathik',
                    'gender' => 'Male',
                    'birth_date' => '2007-12-06',
                    'birth_place' => 'Surabaya',
                    'blood_type' => 'O',
                    'address' => 'Jl. Teluk Nibung Timur 4 No. 48',
                    'phone' => '081234567894',
                    'bpjs_status' => 'Aktif',
                    'bpjs_number' => '0001234567895',
                    'weight' => 58.0,
                    'kyu' => 'II Kyu',
                    'dojo_origin' => 'Sura Dojo Center',
                    'city' => 'Surabaya',
                    'match_type' => 'Kempo',
                    'rank' => 'II Kyu',
                ],
                [
                    'nik' => '3578120809110003',
                    'name' => 'Gylbert Malmstens Djami',
                    'gender' => 'Male',
                    'birth_date' => '2011-09-08',
                    'birth_place' => 'Surabaya',
                    'blood_type' => 'A',
                    'address' => 'Kalimas Baru II GG Buntu No 266',
                    'phone' => '081234567895',
                    'bpjs_status' => 'Aktif',
                    'bpjs_number' => '0001234567896',
                    'weight' => 42.0,
                    'kyu' => 'III Kyu',
                    'dojo_origin' => 'Sura Dojo Academy',
                    'city' => 'Surabaya',
                    'match_type' => 'Kempo',
                    'rank' => 'III Kyu',
                ],
                [
                    'nik' => '3578120101085123',
                    'name' => 'Bilizzati Asyifa Humaira Salavia',
                    'gender' => 'Female',
                    'birth_date' => '2013-08-27',
                    'birth_place' => 'Surabaya',
                    'blood_type' => 'B',
                    'address' => 'Jl. Teluk Penanjung No. 37',
                    'phone' => '081234567896',
                    'bpjs_status' => 'Aktif',
                    'bpjs_number' => '0001234567897',
                    'weight' => 38.0,
                    'kyu' => 'III Kyu',
                    'dojo_origin' => 'Sura Dojo Center',
                    'city' => 'Surabaya',
                    'match_type' => 'Kempo',
                    'rank' => 'III Kyu',
                ],
                [
                    'nik' => '3578164603100001',
                    'name' => 'Annisa Khansa Jamiah',
                    'gender' => 'Female',
                    'birth_date' => '2010-02-19',
                    'birth_place' => 'Surabaya',
                    'blood_type' => 'O',
                    'address' => 'Jl. Teluk Nibung Timur 4 No. 48',
                    'phone' => '081234567897',
                    'bpjs_status' => 'Aktif',
                    'bpjs_number' => '0001234567898',
                    'weight' => 44.0,
                    'kyu' => 'III Kyu',
                    'dojo_origin' => 'Sura Dojo Center',
                    'city' => 'Surabaya',
                    'match_type' => 'Kempo',
                    'rank' => 'III Kyu',
                ],
                [
                    'nik' => '3578125906100003',
                    'name' => 'Quenasha Gendhis Gupita',
                    'gender' => 'Female',
                    'birth_date' => '2010-06-19',
                    'birth_place' => 'Surabaya',
                    'blood_type' => 'AB',
                    'address' => 'Prapat Kurung Tegal No 3B',
                    'phone' => '081234567903',
                    'bpjs_status' => 'Aktif',
                    'bpjs_number' => '0001234567904',
                    'weight' => 41.5,
                    'kyu' => 'IV Kyu',
                    'dojo_origin' => 'Sura Dojo Center',
                    'city' => 'Surabaya',
                    'match_type' => 'Kempo',
                    'rank' => 'IV Kyu',
                ],
                [
                    'nik' => '3578127103110001',
                    'name' => 'Azzizah lucita Zaviera',
                    'gender' => 'Female',
                    'birth_date' => '2011-03-31',
                    'birth_place' => 'Surabaya',
                    'blood_type' => 'O',
                    'address' => 'Jln. Teluk Tomini No. 18',
                    'phone' => '081234567904',
                    'bpjs_status' => 'Aktif',
                    'bpjs_number' => '0001234567905',
                    'weight' => 39.0,
                    'kyu' => 'IV Kyu',
                    'dojo_origin' => 'Sura Dojo Center',
                    'city' => 'Surabaya',
                    'match_type' => 'Kempo',
                    'rank' => 'IV Kyu',
                ],
                [
                    'nik' => '3578122805080001',
                    'name' => 'Rizqy Iman Hanafi',
                    'gender' => 'Male',
                    'birth_date' => '2008-05-28',
                    'birth_place' => 'Surabaya',
                    'blood_type' => 'A',
                    'address' => 'Jl. Teluk Bone Baru No. 24',
                    'phone' => '081234567905',
                    'bpjs_status' => 'Aktif',
                    'bpjs_number' => '0001234567906',
                    'weight' => 56.5,
                    'kyu' => 'IV Kyu',
                    'dojo_origin' => 'Sura Dojo Center',
                    'city' => 'Surabaya',
                    'match_type' => 'Kempo',
                    'rank' => 'IV Kyu',
                ],
                [
                    'nik' => '3515166504130001',
                    'name' => 'Intan Renia Anggraini',
                    'gender' => 'Female',
                    'birth_date' => '2013-04-24',
                    'birth_place' => 'Sidoarjo',
                    'blood_type' => 'B',
                    'address' => 'Jl. Tambak Wedi Baru Utara 18B/28',
                    'phone' => '081234567906',
                    'bpjs_status' => 'Aktif',
                    'bpjs_number' => '0001234567907',
                    'weight' => 37.5,
                    'kyu' => 'IV Kyu',
                    'dojo_origin' => 'Sura Dojo Center',
                    'city' => 'Surabaya',
                    'match_type' => 'Kempo',
                    'rank' => 'IV Kyu',
                ],
                [
                    'nik' => '3578157006160016',
                    'name' => 'Binar Rizky Ramadhani Solichin',
                    'gender' => 'Female',
                    'birth_date' => '2016-06-30',
                    'birth_place' => 'Surabaya',
                    'blood_type' => 'AB',
                    'address' => 'Jl. Ikan Gurami 6/11',
                    'phone' => '081234567907',
                    'bpjs_status' => 'Aktif',
                    'bpjs_number' => '0001234567908',
                    'weight' => 30.0,
                    'kyu' => 'IV Kyu',
                    'dojo_origin' => 'Sura Dojo Center',
                    'city' => 'Surabaya',
                    'match_type' => 'Kempo',
                    'rank' => 'IV Kyu',
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
                    'match_type' => 'Kempo',
                    'rank' => 'Kyu 1',
                ],
                [
                    'nik' => '3578164603100002',
                    'name' => 'Jasmine Nur Erika',
                    'gender' => 'Female',
                    'birth_date' => '2010-03-06',
                    'birth_place' => 'Sidoarjo',
                    'blood_type' => 'A',
                    'address' => 'Jl. Tambak Wedi Baru Utara 18B/28',
                    'phone' => '081234567898',
                    'bpjs_status' => 'Aktif',
                    'bpjs_number' => '0001234567899',
                    'weight' => 43.0,
                    'kyu' => 'III Kyu',
                    'dojo_origin' => 'Sura Dojo Center',
                    'city' => 'Surabaya',
                    'match_type' => 'Kempo',
                    'rank' => 'III Kyu',
                ],
                [
                    'nik' => '3578126707060003',
                    'name' => 'Balqis Rizky Solichin',
                    'gender' => 'Female',
                    'birth_date' => '2006-07-27',
                    'birth_place' => 'Surabaya',
                    'blood_type' => 'AB',
                    'address' => 'Jl. Ikan Gurami 6/11',
                    'phone' => '081234567899',
                    'bpjs_status' => 'Aktif',
                    'bpjs_number' => '0001234567900',
                    'weight' => 54.0,
                    'kyu' => 'III Kyu',
                    'dojo_origin' => 'Sura Dojo Center',
                    'city' => 'Surabaya',
                    'match_type' => 'Kempo',
                    'rank' => 'III Kyu',
                ],
                [
                    'nik' => '3578121908050002',
                    'name' => 'Geordane Gavriel Axel Radja',
                    'gender' => 'Male',
                    'birth_date' => '2005-08-19',
                    'birth_place' => 'Surabaya',
                    'blood_type' => 'O',
                    'address' => 'Jl. Kalimas Baru 2/258',
                    'phone' => '081234567900',
                    'bpjs_status' => 'Aktif',
                    'bpjs_number' => '0001234567901',
                    'weight' => 61.0,
                    'kyu' => 'III Kyu',
                    'dojo_origin' => 'Sura Dojo Center',
                    'city' => 'Surabaya',
                    'match_type' => 'Kempo',
                    'rank' => 'III Kyu',
                ],
                [
                    'nik' => '3578121810060001',
                    'name' => 'Beryl Oktoven Nepa Fay',
                    'gender' => 'Male',
                    'birth_date' => '2006-10-18',
                    'birth_place' => 'Surabaya',
                    'blood_type' => 'A',
                    'address' => 'Jl. Johor Baru DKA 62',
                    'phone' => '081234567901',
                    'bpjs_status' => 'Aktif',
                    'bpjs_number' => '0001234567902',
                    'weight' => 59.5,
                    'kyu' => 'III Kyu',
                    'dojo_origin' => 'Sura Dojo Center',
                    'city' => 'Surabaya',
                    'match_type' => 'Kempo',
                    'rank' => 'III Kyu',
                ],
                [
                    'nik' => '3578122804090001',
                    'name' => 'Bisma Ali Kumara',
                    'gender' => 'Male',
                    'birth_date' => '2009-09-28',
                    'birth_place' => 'Surabaya',
                    'blood_type' => 'B',
                    'address' => 'Krembangan Bhakti/GG 4/No 6',
                    'phone' => '081234567902',
                    'bpjs_status' => 'Aktif',
                    'bpjs_number' => '0001234567903',
                    'weight' => 57.0,
                    'kyu' => 'III Kyu',
                    'dojo_origin' => 'Sura Dojo Center',
                    'city' => 'Surabaya',
                    'match_type' => 'Kempo',
                    'rank' => 'III Kyu',
                ],
                [
                    'nik' => '3578123001160004',
                    'name' => 'Jericho Treemonti Djami',
                    'gender' => 'Male',
                    'birth_date' => '2015-01-30',
                    'birth_place' => 'Surabaya',
                    'blood_type' => 'O',
                    'address' => 'Kalimas Baru II GG Buntu No 266',
                    'phone' => '081234567908',
                    'bpjs_status' => 'Aktif',
                    'bpjs_number' => '0001234567909',
                    'weight' => 35.0,
                    'kyu' => 'V Kyu',
                    'dojo_origin' => 'Sura Dojo Center',
                    'city' => 'Surabaya',
                    'match_type' => 'Kempo',
                    'rank' => 'V Kyu',
                ],
                [
                    'nik' => '3578126404140002',
                    'name' => 'Alyssa Putri Djumaidah',
                    'gender' => 'Female',
                    'birth_date' => '2014-04-24',
                    'birth_place' => 'Surabaya',
                    'blood_type' => 'A',
                    'address' => 'Jl. Teluk Nibung Timur 4 No. 48',
                    'phone' => '081234567909',
                    'bpjs_status' => 'Aktif',
                    'bpjs_number' => '0001234567910',
                    'weight' => 33.0,
                    'kyu' => 'V Kyu',
                    'dojo_origin' => 'Sura Dojo Center',
                    'city' => 'Surabaya',
                    'match_type' => 'Kempo',
                    'rank' => 'V Kyu',
                ],
                [
                    'nik' => '3578126404140001',
                    'name' => 'Aisyah Putri Jamiah',
                    'gender' => 'Female',
                    'birth_date' => '2014-04-24',
                    'birth_place' => 'Surabaya',
                    'blood_type' => 'B',
                    'address' => 'Jl. Teluk Nibung Timur 4 No. 48',
                    'phone' => '081234567910',
                    'bpjs_status' => 'Aktif',
                    'bpjs_number' => '0001234567911',
                    'weight' => 34.0,
                    'kyu' => 'V Kyu',
                    'dojo_origin' => 'Sura Dojo Center',
                    'city' => 'Surabaya',
                    'match_type' => 'Kempo',
                    'rank' => 'V Kyu',
                ],
                [
                    'nik' => '3578124506960001',
                    'name' => 'Pangesti Nur Mardiah',
                    'gender' => 'Female',
                    'birth_date' => '1996-06-05',
                    'birth_place' => 'Surabaya',
                    'blood_type' => 'AB',
                    'address' => 'Teluk Aru Utara No. 61 B',
                    'phone' => '081234567911',
                    'bpjs_status' => 'Aktif',
                    'bpjs_number' => '0001234567912',
                    'weight' => 55.0,
                    'kyu' => 'I Kyu',
                    'dojo_origin' => 'Sura Dojo Center',
                    'city' => 'Surabaya',
                    'match_type' => 'Kempo',
                    'rank' => 'I Kyu',
                ],
                [
                    'nik' => '3578170801130028',
                    'name' => 'Tohdjoyo Rusmono',
                    'gender' => 'Male',
                    'birth_date' => '1971-09-06',
                    'birth_place' => 'Surabaya',
                    'blood_type' => 'O',
                    'address' => 'Sidomulyo Gg.2A/26 Surabaya',
                    'phone' => '081234567912',
                    'bpjs_status' => 'Aktif',
                    'bpjs_number' => '0001234567913',
                    'weight' => 70.0,
                    'kyu' => 'I Kyu',
                    'dojo_origin' => 'Sura Dojo Center',
                    'city' => 'Surabaya',
                    'match_type' => 'Kempo',
                    'rank' => 'I Kyu',
                ],
            ],

        ];

        $currentAthletes = $athletesData[$contingentType] ?? [];

        foreach ($currentAthletes as $athleteData) {
            $birthDate = Carbon::parse($athleteData['birth_date'] ?? '2000-01-01');
            $age = $birthDate->age;

            // Determine Age Group based on age
            $ageGroup = 'Dewasa A';
            if ($age < 12) {
                $ageGroup = 'Pemula';
            } elseif ($age >= 12 && $age <= 14) {
                $ageGroup = 'Remaja A';
            } elseif ($age >= 15 && $age <= 17) {
                $ageGroup = 'Remaja B';
            } elseif ($age >= 35) {
                $ageGroup = 'Dewasa B (Senior)';
            }

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
                'age_group' => $athleteData['age_group'] ?? $ageGroup,
                'match_type' => $athleteData['match_type'],
                'rank' => $athleteData['rank'],
                'age' => $age,
            ];

            $athlete = Athlete::create($masterData);

            // Initial membership
            $athlete->contingents()->attach([
                $contingent->id => ['is_primary' => true, 'joined_at' => now()],
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
                ->where('age_group', $pivotData['age_group'])
                ->limit(2)
                ->get();

            if ($categories->isNotEmpty()) {
                $athlete->categories()->syncWithPivotValues($categories->pluck('id')->toArray(), ['registration_id' => $registration->id]);
            }
        }
    }
}
