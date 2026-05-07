<?php

namespace Database\Seeders;

use App\Models\Athlete;
use App\Models\Contingent;
use App\Models\Group\AgeGroup;
use App\Models\Group\WeightGroup;
use App\Models\MatchNumber\MatchNumber;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TournamentDummySeeder extends Seeder
{
    public function run(): void
    {
        // 1. Clear existing dynamic data to start fresh
        DB::statement('SET session_replication_role = \'replica\';');
        DB::table('athlete_match_number')->truncate();
        DB::table('registration_athlete')->truncate();
        DB::table('registration_official')->truncate();
        DB::table('athlete_contingent')->truncate();
        Registration::truncate();
        Athlete::truncate();
        Contingent::truncate();
        User::role('Contingent')->delete();
        DB::statement('SET session_replication_role = \'origin\';');

        // 2. Load basic master data
        $weightGroups = WeightGroup::all();
        $ageGroups = AgeGroup::all()->keyBy('name');

        // Ensure "Dewasa" is mapped to "Dewasa A" if necessary
        if ($ageGroups->has('Dewasa A') && ! $ageGroups->has('Dewasa')) {
            $ageGroups->put('Dewasa', $ageGroups->get('Dewasa A'));
        }

        // 3. Define the Tournament Data from User Request
        $tournamentData = $this->getTournamentData();

        $this->command->info('Seeding specific tournament data...');

        // 4. Create unique contingents
        $allContingentNames = [];
        foreach ($tournamentData as $ageName => $sections) {
            foreach ($sections as $genderKey => $matches) {
                foreach ($matches as $match) {
                    foreach ($match['contingents'] as $cName) {
                        $cleanName = preg_replace('/\s*\(.*?\)/', '', $cName);
                        $cleanName = preg_replace('/\s*\[.*?\]/', '', $cleanName);
                        $cleanName = trim($cleanName);
                        if (! empty($cleanName)) {
                            $allContingentNames[strtolower($cleanName)] = $cleanName;
                        }
                    }
                }
            }
        }

        $contingentModels = [];

        foreach ($allContingentNames as $slug => $cName) {
            $email = str_replace(' ', '', $slug).'@dummy.test';
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $cName,
                    'password' => Hash::make('password'),
                ]
            );

            if (! $user->hasRole('Contingent')) {
                $user->assignRole('Contingent');
            }

            $contingentModels[$cName] = Contingent::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'name' => $cName,
                    'kab_kota' => $cName,
                    'leader_name' => 'Leader '.$cName,
                    'leader_phone' => '08'.rand(1000000000, 9999999999),
                    'email' => $email,
                    'address' => 'Alamat '.$cName,
                ]
            );

            if (! $contingentModels[$cName]->registrations()->exists()) {
                Registration::create([
                    'contingent_id' => $contingentModels[$cName]->id,
                    'total_cost' => 2500000,
                    'final_amount' => 2500000 + rand(100, 999),
                    'unique_code' => rand(100, 999),
                    'payment_method' => 'Transfer',
                    'status' => 'verified',
                    'sim_perkemi_confirm' => 'Ya',
                ]);
            }
        }

        // 5. Create Match Numbers and Assign Athletes
        foreach ($tournamentData as $ageName => $sections) {
            $ageGroup = $ageGroups->get($ageName);
            if (! $ageGroup) {
                $ageGroup = AgeGroup::create(['name' => $ageName, 'price' => 500000, 'order' => 99]);
                $ageGroups->put($ageName, $ageGroup);
            }

            foreach ($sections as $genderKey => $matches) {
                $gender = match ($genderKey) {
                    'Putra' => 'Male',
                    'Putri' => 'Female',
                    default => 'Mix',
                };

                foreach ($matches as $matchInfo) {
                    $matchName = $matchInfo['name'];
                    $draftType = str_contains(strtolower($matchName), 'randori') ? 'randori' : 'embu';
                    $maxAthletes = 1;
                    if (str_contains(strtolower($matchName), 'pasangan')) {
                        $maxAthletes = 2;
                    }
                    if (str_contains(strtolower($matchName), 'beregu')) {
                        $maxAthletes = 4;
                    }

                    $matchNumber = MatchNumber::firstOrCreate(
                        [
                            'name' => $matchName,
                            'age_group_id' => $ageGroup->id,
                            'gender' => $gender,
                        ],
                        [
                            'match_id' => $matchInfo['match_id'] ?? null,
                            'draft_type' => $draftType,
                            'max_athletes' => $maxAthletes,
                            'order' => 1,
                        ]
                    );

                    foreach ($matchInfo['contingents'] as $cString) {
                        $cClean = trim(preg_replace('/\s*\(.*?\)/', '', $cString));
                        $cClean = trim(preg_replace('/\s*\[.*?\]/', '', $cClean));

                        $contingent = null;
                        foreach ($contingentModels as $name => $model) {
                            if (strtolower($name) === strtolower($cClean)) {
                                $contingent = $model;
                                break;
                            }
                        }

                        if (! $contingent) {
                            continue;
                        }

                        $entryGender = $gender;
                        if (str_contains($cString, '[putra]')) {
                            $entryGender = 'Male';
                        }
                        if (str_contains($cString, '[putri]')) {
                            $entryGender = 'Female';
                        }
                        if (str_contains($cString, '[campuran]')) {
                            $entryGender = 'Mix';
                        }

                        $registration = $contingent->registrations()->latest()->first();

                        preg_match('/\((.*?)\)/', $cString, $matches_ath);
                        $athNames = isset($matches_ath[1]) ? explode('+', $matches_ath[1]) : [];
                        if (count($athNames) === 0) {
                            preg_match('/\((.*?)\)/', $cString, $matches_ath2);
                            if (isset($matches_ath2[1])) {
                                $athNames = explode('/', $matches_ath2[1]);
                            }
                        }

                        if (empty($athNames)) {
                            for ($i = 0; $i < $maxAthletes; $i++) {
                                $athNames[] = 'Atlet '.($i + 1).' '.$cClean;
                            }
                        }

                        foreach ($athNames as $aName) {
                            $aName = trim($aName);
                            if (empty($aName)) {
                                continue;
                            }

                            // Ensure unique NIK
                            do {
                                $nik = fake()->numerify('################');
                            } while (Athlete::where('nik', $nik)->exists());

                            $athlete = Athlete::create([
                                'nik' => $nik,
                                'name' => $aName,
                                'gender' => $entryGender === 'Mix' ? (rand(0, 1) ? 'Male' : 'Female') : $entryGender,
                                'birth_place' => 'Kota '.$cClean,
                                'birth_date' => now()->subYears(rand(10, 25))->format('Y-m-d'),
                                'phone' => '08'.fake()->numerify('##########'),
                                'bpjs_status' => 'Aktif',
                            ]);

                            // Link to contingent (primary)
                            $athlete->contingents()->attach($contingent->id, [
                                'is_primary' => true,
                                'joined_at' => now(),
                            ]);

                            // Link to registration via pivot
                            $registration->athletes()->attach($athlete->id, [
                                'kyu' => 'Kyu 3',
                                'age_group' => $ageName,
                                'rank' => 'Kyu 3',
                                'match_type' => ucfirst($draftType),
                                'city' => $cClean,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);

                            // Link to match number
                            DB::table('athlete_match_number')->insert([
                                'athlete_id' => $athlete->id,
                                'match_number_id' => $matchNumber->id,
                                'registration_id' => $registration->id,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        }
                    }
                }
            }
        }

        $this->command->info('✅ Tournament dummy data seeded successfully!');
    }

    private function getTournamentData(): array
    {
        return [
            'Pemula' => [
                'Putra' => [
                    ['match_id' => 'P1', 'name' => 'Embu Tandoku kyu kenshi eksebisi', 'contingents' => ['Kab Jombang', 'Kab Gresik', 'Surabaya A (fakhry)', 'Surabaya B (Kael)', 'Surabaya C (Tegar)', 'Kota malang 3 (Stiba GSE)', 'Kab tuban', 'Kab tuban']],
                ],
                'Putri' => [
                    ['match_id' => 'P2', 'name' => 'Embu Tandoku kyu kenshi', 'contingents' => ['Bangkalan B', 'Kab Banyuwangi', 'Kab Jombang', 'Kab Gresik', 'Surabaya A ( izzati)', 'Surabaya B (kekei)', 'Surabaya C ( Beby)', 'Kota malang 3 (Stiba GSE)', 'Kab tuban']],
                ],
                'Campuran' => [
                    ['match_id' => 'P3', 'name' => 'Embu Pasangan campuran Kyu kenshi', 'contingents' => ['Kab Jombang', 'Surabaya D (lucky+maribeth)', 'Surabaya C ( Beby + Rafa )', 'Surabaya A (ilham+izzati)', 'Surabaya B (Kael + Kekei)', 'Kota malang 3 (Stiba GSE)', 'Kab Tuban']],
                    ['match_id' => 'P4', 'name' => 'Embu Pasangan putra/putri Kyu kenshi eksebisi', 'contingents' => ['Kab Jombang', 'Kab Gresik', 'Surabaya B (arvie+aldan)', 'Surabaya C ( Tegar + Hanif)', 'Kab Gresik', 'Surabaya A (fakhry+ilham)', 'Kab Gresik [putri]', 'Surabaya A (alyssa+aisyah) [putri]']],
                ],
            ],
            'Remaja A' => [
                'Putra' => [
                    ['match_id' => 'RA1', 'name' => 'embu tandoku kyu kenshi eksebisi', 'contingents' => ['Kab Banyuwangi', 'Kota Malang', 'Kab Jombang', 'Kab Gresik', 'Surabaya A (yahya)', 'Surabaya B (Javier)', 'Kota Malang', 'Sidoarjo', 'Kab Tuban', 'Kab Tuban']],
                ],
                'Putri' => [
                    ['match_id' => 'RA2', 'name' => 'Embu Tandoku Kyu kenshi eksebisi', 'contingents' => ['Kab Jombang', 'Kab Gresik', 'Surabaya A (kirana/sarah)', 'Surabaya B (Athalia/Aura/Intan)', 'Kota Malang', 'Kota malang 3 (Stiba GSE)', 'Kab Tuban', 'Kab Tuban']],
                ],
                'Campuran' => [
                    ['match_id' => 'RA3', 'name' => 'Embu Pasangan Kyu kenshi Putra/Putri/Campuran eksebisi', 'contingents' => ['Kab Gresik [campuran]', 'Surabaya A (raung+kirana/sarah) [campuran]', 'Surabaya B (jojo + Intan) [campuran]', 'Kota Malang [campuran]', 'sidoarjo [campuran]', 'Surabaya A (Kirana + Sarah) [putri]', 'Surabaya B (Aura + Athalia) [putri]', 'Kota malang 3 (Stiba GSE) [putri]', 'Surabaya A (raung + yahya) [putra]', 'sidoarjo [putra]', 'Kab Tuban', 'Kab Tuban']],
                    ['match_id' => 'RA4', 'name' => 'Embu Beregu putra/putri/campuran eksebisi', 'contingents' => ['Surabaya A (Yahya + Raung + Kirana + Sarah)', 'Surabaya A (farzha+kaysha+naisya+dinda)', 'Sidoarjo', 'Kab tuban', 'Kab Tuban']],
                ],
            ],
            'Remaja B' => [
                'Putra' => [
                    ['match_id' => 'RB1', 'name' => 'embu tandoku kyu 4/3', 'contingents' => ['Kab Banyuwangi', 'Kota Malang', 'Kab Jombang', 'Surabaya A (Fais)', 'Surabaya C (Ibrahim)']],
                    ['match_id' => 'RB2', 'name' => 'embu tandoku kyu 2/1', 'contingents' => ['Kab Jember', 'Kota Malang', 'Kab Jombang', 'Surabaya A (Gio/Desta)', 'Kab Tuban']],
                    ['match_id' => 'RB3', 'name' => 'embu pasangan kyu kenshi eksebisi', 'contingents' => ['Bangkalan B', 'Kab Banyuwangi', 'Surabaya A (bisma + Alree)', 'Kab Jombang', 'Surabaya A (Gio+Desta)', 'Surabaya B (reinhard + delon)']],
                    ['match_id' => 'RB4', 'name' => 'Randori 45Kg', 'contingents' => ['Bangkalan A', 'Kab Banyuwangi', 'Kota Malang', 'Kab Jombang', 'Kota Kediri', 'Surabaya A (nando)']],
                    ['match_id' => 'RB5', 'name' => 'Randori 50Kg', 'contingents' => ['Kab Jember', 'Kab Banyuwangi', 'Kota Malang', 'Kab Jombang', 'Kota Kediri', 'sidoarjo']],
                    ['match_id' => 'RB6', 'name' => 'Randori 55Kg', 'contingents' => ['Bangkalan B', 'Kab Banyuwangi', 'Kota Malang', 'Kab Jombang', 'Kota Kediri', 'Surabaya A (Gio)', 'sidoarjo','Lumajang']],
                    ['match_id' => 'RB7', 'name' => 'Randori 60Kg', 'contingents' => ['Bangkalan B', 'Bangkalan A', 'Kab Banyuwangi', 'Kota Malang', 'Kab Jombang', 'Kab Gresik', 'Kota Kediri', 'Surabaya A (Bisma)', 'Sidoarjo', 'Kab Tuban']],
                    ['match_id' => 'RB8', 'name' => 'Randori 65Kg', 'contingents' => ['Kota Malang', 'Kab Jombang', 'Kab Gresik', 'Kota Kediri', 'Surabaya A (desta)']],
                    ['match_id' => 'RB9', 'name' => 'Randori 70Kg', 'contingents' => ['Surabaya A (troy)', 'Surabaya B (Reindhard)', 'Surabaya C (Ibrahim)', 'Surabaya D (Aaron)']],
                    ['match_id' => 'RB10', 'name' => 'Randori >70Kg', 'contingents' => ['Kota Malang', 'Surabaya B ( delon)', 'Surabaya D (Jadon)', 'Surabaya A (Fais)']],
                ],
                'Putri' => [
                    ['match_id' => 'RB11', 'name' => 'embu tandoku kyu 4/3', 'contingents' => ['Bangkalan A', 'Kab Jember', 'Kab Banyuwangi', 'Kab Jombang']],
                    ['match_id' => 'RB12', 'name' => 'embu tandoku kyu 2/1', 'contingents' => ['Kab Jember', 'Kota Malang', 'Kab Jombang', 'Surabaya A (Aqila)', 'Kota malang 3 (Stiba GSE)']],
                    ['match_id' => 'RB13', 'name' => 'embu pasangan kyu Kenshi eksebisi', 'contingents' => ['Bangkalan B', 'Kab Banyuwangi', 'Kab Jombang', 'Kab Pasuruan', 'surabaya B (jasmine + gendhis)', 'Kab Jombang', 'Kota malang 3 (Stiba GSE)']],
                    ['match_id' => 'RB14', 'name' => 'Randori 45Kg', 'contingents' => ['Bangkalan A', 'Kab Banyuwangi', 'Kab Jombang', 'Kota Kediri', 'Kab Pasuruan', 'Surabaya A (anisa )', 'Surabaya B ( Abygel)']],
                    ['match_id' => 'RB15', 'name' => 'Randori 50Kg', 'contingents' => ['Bangkalan B', 'Kab Jember', 'Kab Banyuwangi', 'Kab Jombang', 'Kab Pasuruan', 'Surabaya A ( Lucita )']],
                    ['match_id' => 'RB16', 'name' => 'Randori 55Kg', 'contingents' => ['Kab Jember', 'Kab Banyuwangi', 'Kota Malang', 'Kab Jombang', 'Kota Kediri', 'Kab Pasuruan', 'Surabaya B (gendhis)']],
                    ['match_id' => 'RB17', 'name' => 'Randori 70Kg eksebisi', 'contingents' => ['Bangkalan B', 'Surabaya B (jasmine)', 'Surabaya A (farah)']],
                ],
                'Campuran' => [
                    ['match_id' => 'RB18', 'name' => 'Embu Pasangan Kyu kenshi eksebisi', 'contingents' => ['Surabaya A (annisa+bisma)', 'Kota Malang', 'Kab Jombang', 'Surabaya A (aqila+nando)']],
                    ['match_id' => 'RB19', 'name' => 'Embu Beregu putra/putri/campuran eksebisi', 'contingents' => ['Kota Malang [campuran]', 'Kab Jombang [campuran]', 'kab jombang [putri]', 'Kota Malang [putra]', 'Kab Jombang [putra]']],
                ],
            ],
            'Dewasa' => [
                'Putra' => [
                    ['match_id' => 'D1', 'name' => 'embu tandoku kyu 3/2 eksebisi', 'contingents' => ['Bangkalan A', 'Kab Jember', 'Kota Malang', 'Kab Jember']],
                    ['match_id' => 'D2', 'name' => 'embu tandoku kyu 1', 'contingents' => ['Bangkalan A', 'Kab Jember', 'Kab Jombang', 'Surabaya A (dani)']],
                    ['match_id' => 'D3', 'name' => 'Randori 50Kg eksebisi', 'contingents' => ['Bangkalan A', 'Kab Jember', 'Kota Kediri']],
                    ['match_id' => 'D4', 'name' => 'Randori 55Kg eksebisi', 'contingents' => ['Kota Kediri', 'Kab Pasuruan', 'kota malang']],
                    ['match_id' => 'D5', 'name' => 'Randori 60Kg eksebisi', 'contingents' => ['Bangkalan A', 'Kab Jember', 'Kota Malang', 'Kab Jombang', 'Kota Kediri', 'Kab Pasuruan', 'Kota malang 3 (Stiba GSE)', 'kota kediri']],
                    ['match_id' => 'D6', 'name' => 'Randori 65Kg', 'contingents' => ['Kota Malang', 'Kab Jombang', 'Surabaya A (dani)', 'kota kediri']],
                    ['match_id' => 'D7', 'name' => 'Randori 70Kg eksebisi', 'contingents' => ['Kota Kediri', 'kota kediri', 'Bangkalan A', 'Kab Jember']],
                ],
                'Putri' => [
                    ['match_id' => 'D8', 'name' => 'embu tandoku kyu 3/2 eksebisi', 'contingents' => ['Kab Banyuwangi', 'Kab Jember', 'Kab Banyuwangi', 'Kab Pasuruan']],
                    ['match_id' => 'D9', 'name' => 'embu tandoku kyu 1', 'contingents' => ['Kab Jember', 'Kab Banyuwangi', 'Kota Malang', 'Kab Jombang', 'Surabaya A (bilqis)']],
                    ['match_id' => 'D10', 'name' => 'Randori 45Kg', 'contingents' => ['Bangkalan A', 'Kab Banyuwangi', 'Kab Jombang', 'Kota Kediri', 'Kab Pasuruan']],
                    ['match_id' => 'D11', 'name' => 'Randori 50Kg', 'contingents' => ['Bangkalan B', 'Kab Jember', 'Kab Banyuwangi', 'Kab Jombang', 'Kab Pasuruan', 'Surabaya A ( Lucita )']],
                    ['match_id' => 'D12', 'name' => 'Randori 55Kg', 'contingents' => ['Kab Jember', 'Kab Banyuwangi', 'Kota Malang', 'Kab Jombang', 'Kab Pasuruan', 'surabaya A (cindy)']],
                    ['match_id' => 'D13', 'name' => 'Randori 60Kg', 'contingents' => ['Kab Jember', 'Kab Banyuwangi', 'Kota Malang', 'surabaya A (stephanie)']],
                    ['match_id' => 'D14', 'name' => 'Randori 65Kg', 'contingents' => ['Kab Banyuwangi', 'surabaya b (erra)', 'surabaya c (laverda)', 'surabaya A (ocha)']],
                ],
                'Campuran' => [
                    ['match_id' => 'D15', 'name' => 'Embu Pasangan Kyu kenshi putra/putri/campuran', 'contingents' => ['Bangkalan A [campuran]', 'Surabaya A (dani + ocha) [campuran]', 'kab banyuwangi [putri]', 'kota malang [putri]', 'surabaya B (bilqis + erra) [putri]', 'kab jombang [putra]']],
                    ['match_id' => 'D16', 'name' => 'Embu tandoku yudansha putra/putri eksebisi', 'contingents' => ['kota malang [putra]', 'kota malang [putri]', 'surabaya A (cindy) [putri]', 'kota malang [putra]', 'surabaya B (rafi) [putra]', 'surabaya C (noval) [putra]']],
                ],
            ],
        ];
    }
}
