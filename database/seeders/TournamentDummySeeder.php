<?php

namespace Database\Seeders;

use App\Models\Athlete;
use App\Models\Contingent;
use App\Models\Court\Court;
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

        for ($i = 1; $i <= 3; $i++) {
            Court::create([
                'name' => 'Court '.$i,
                'order' => $i,
            ]);
        }

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
                    $lowerName = strtolower($matchName);

                    // Detect if name contains multiple genders to split
                    $targetGenders = [];
                    if (str_contains($lowerName, 'putra/putri/campuran')) {
                        $targetGenders = ['Male', 'Female', 'Mix'];
                    } elseif (str_contains($lowerName, 'putra/putri')) {
                        $targetGenders = ['Male', 'Female'];
                    } else {
                        $targetGenders = [$gender]; // Default from category key
                    }

                    // Distribute contingents among target genders if more than one
                    $allContingents = $matchInfo['contingents'];
                    $numGenders = count($targetGenders);
                    $contingentChunks = [];
                    
                    if ($numGenders > 1) {
                        // Distribute evenly, e.g. 8 -> 3, 3, 2
                        $chunkSize = ceil(count($allContingents) / $numGenders);
                        $contingentChunks = array_chunk($allContingents, (int)$chunkSize);
                    } else {
                        $contingentChunks = [$allContingents];
                    }

                    foreach ($targetGenders as $idx => $g) {
                        $currentMatchContingents = $contingentChunks[$idx] ?? [];
                        if (empty($currentMatchContingents)) continue;

                        $draftType = str_contains($lowerName, 'randori') ? 'randori' : 'embu';
                        $maxAthletes = 1;
                        if (str_contains($lowerName, 'pasangan')) {
                            $maxAthletes = 2;
                        }
                        if (str_contains($lowerName, 'beregu')) {
                            $maxAthletes = 4;
                        }

                        // Clean up name and append gender suffix if splitting
                        $cleanMatchName = $matchName;
                        if ($numGenders > 1) {
                            $genderSuffix = match ($g) {
                                'Male' => ' (Putra)',
                                'Female' => ' (Putri)',
                                'Mix' => ' (Campuran)',
                                default => ''
                            };
                            $cleanMatchName = preg_replace('/\s*putra\/putri(\/campuran)?/i', '', $matchName).$genderSuffix;
                        }

                        $matchNumber = MatchNumber::firstOrCreate(
                            [
                                'name' => $cleanMatchName,
                                'age_group_id' => $ageGroup->id,
                                'gender' => $g,
                            ],
                            [
                                'match_id' => $numGenders > 1
                                    ? ($matchInfo['match_id'].'-'.substr($g, 0, 1))
                                    : ($matchInfo['match_id'] ?? null),
                                'draft_type' => $draftType,
                                'max_athletes' => $maxAthletes,
                                'order' => 1,
                            ]
                        );

                        // Assign athletes to the current (possibly split) match number
                        foreach ($currentMatchContingents as $cString) {
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

                            // Use the split gender ($g) for athlete gender unless explicitly overridden in brackets
                            $entryGender = $g;
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
        }

        $this->command->info('✅ Tournament dummy data seeded successfully!');
    }

    private function getTournamentData(): array
    {
        return [
            'Pemula' => [
                'Putra' => [
                    ['match_id' => 'P1', 'name' => 'Embu Tandoku kyu kenshi eksebisi', 'contingents' => ['Kab Jombang', 'Kab Gresik', 'Surabaya A', 'Surabaya B', 'Surabaya C', 'Kota malang 3', 'Kab tuban', 'Kab tuban']],
                ],
                'Putri' => [
                    ['match_id' => 'P2', 'name' => 'Embu Tandoku kyu kenshi', 'contingents' => ['Bangkalan B', 'Kab Banyuwangi', 'Kab Jombang', 'Kab Gresik', 'Surabaya A', 'Surabaya B', 'Surabaya C', 'Kota malang 3', 'Kab tuban']],
                ],
                'Campuran' => [
                    ['match_id' => 'P3', 'name' => 'Embu Pasangan campuran Kyu kenshi', 'contingents' => ['Kab Jombang', 'Surabaya D', 'Surabaya C', 'Surabaya A', 'Surabaya B', 'Kota malang 3', 'Kab Tuban']],
                    ['match_id' => 'P4', 'name' => 'Embu Pasangan putra/putri Kyu kenshi eksebisi', 'contingents' => ['Kab Jombang', 'Kab Gresik', 'Surabaya B', 'Surabaya C', 'Kab Gresik', 'Surabaya A', 'Kab Gresik', 'Surabaya A']],
                ],
            ],
            'Remaja A' => [
                'Putra' => [
                    ['match_id' => 'RA1', 'name' => 'embu tandoku kyu kenshi eksebisi', 'contingents' => ['Kab Banyuwangi', 'Kota Malang', 'Kab Jombang', 'Kab Gresik', 'Surabaya A', 'Surabaya B', 'Kota Malang', 'Sidoarjo', 'Kab Tuban', 'Kab Tuban']],
                ],
                'Putri' => [
                    ['match_id' => 'RA2', 'name' => 'Embu Tandoku Kyu kenshi eksebisi', 'contingents' => ['Kab Jombang', 'Kab Gresik', 'Surabaya A', 'Surabaya B', 'Kota Malang', 'Kota malang 3', 'Kab Tuban', 'Kab Tuban']],
                ],
                'Campuran' => [
                    ['match_id' => 'RA3', 'name' => 'Embu Pasangan Kyu kenshi Putra/Putri/Campuran eksebisi', 'contingents' => ['Kab Gresik', 'Surabaya A', 'Surabaya B', 'Kota Malang', 'sidoarjo', 'Surabaya A', 'Surabaya B', 'Kota Malang', 'Surabaya A', 'sidoarjo', 'Kab Tuban', 'Kab Tuban']],
                    ['match_id' => 'RA4', 'name' => 'Embu Beregu putra/putri/campuran eksebisi', 'contingents' => ['Surabaya A', 'Surabaya A', 'Sidoarjo', 'Kab Tuban', 'Kab Tuban']],
                ],
            ],
            'Remaja B' => [
                'Putra' => [
                    ['match_id' => 'RB1', 'name' => 'embu tandoku kyu 4/3', 'contingents' => ['Kab Banyuwangi', 'Kota Malang', 'Kab Jombang', 'Surabaya A', 'Surabaya C']],
                    ['match_id' => 'RB2', 'name' => 'embu tandoku kyu 2/1', 'contingents' => ['Kab Jember', 'Kota Malang', 'Kab Jombang', 'Surabaya A', 'Kab Tuban']],
                    ['match_id' => 'RB3', 'name' => 'embu pasangan kyu kenshi eksebisi', 'contingents' => ['Bangkalan B', 'Kab Banyuwangi', 'Surabaya A', 'Kab Jombang', 'Surabaya A', 'Surabaya B']],
                    ['match_id' => 'RB4', 'name' => 'Randori 45Kg', 'contingents' => ['Bangkalan A', 'Kab Banyuwangi', 'Kota Malang', 'Kab Jombang', 'Kota Kediri', 'Surabaya A']],
                    ['match_id' => 'RB5', 'name' => 'Randori 50Kg', 'contingents' => ['Kab Jember', 'Kab Banyuwangi', 'Kota Malang', 'Kab Jombang', 'Kota Kediri', 'sidoarjo']],
                    ['match_id' => 'RB6', 'name' => 'Randori 55Kg', 'contingents' => ['Bangkalan B', 'Kab Banyuwangi', 'Kota Malang', 'Kab Jombang', 'Kota Kediri', 'Surabaya A', 'sidoarjo']],
                    ['match_id' => 'RB7', 'name' => 'Randori 60Kg', 'contingents' => ['Bangkalan B', 'Bangkalan A', 'Kab Banyuwangi', 'Kota Malang', 'Kab Jombang', 'Kab Gresik', 'Kota Kediri', 'Surabaya A', 'Sidoarjo', 'Kab Tuban']],
                    ['match_id' => 'RB8', 'name' => 'Randori 65Kg', 'contingents' => ['Kota Malang', 'Kab Jombang', 'Kab Gresik', 'Kota Kediri', 'Surabaya A']],
                    ['match_id' => 'RB9', 'name' => 'Randori 70Kg', 'contingents' => ['Surabaya A', 'Surabaya B', 'Surabaya C', 'Surabaya D']],
                    ['match_id' => 'RB10', 'name' => 'Randori >70Kg', 'contingents' => ['Kota Malang', 'Surabaya B', 'Surabaya D', 'Surabaya A']],
                ],
                'Putri' => [
                    ['match_id' => 'RB11', 'name' => 'embu tandoku kyu 4/3', 'contingents' => ['Bangkalan A', 'Kab Jember', 'Kab Banyuwangi', 'Kab Jombang']],
                    ['match_id' => 'RB12', 'name' => 'embu tandoku kyu 2/1', 'contingents' => ['Kab Jember', 'Kota Malang', 'Kab Jombang', 'Surabaya A', 'Kota malang 3']],
                    ['match_id' => 'RB13', 'name' => 'embu pasangan kyu Kenshi eksebisi', 'contingents' => ['Bangkalan B', 'Kab Banyuwangi', 'Kab Jombang', 'Kab Pasuruan', 'surabaya B', 'Kab Jombang', 'Kota malang 3']],
                    ['match_id' => 'RB14', 'name' => 'Randori 45Kg', 'contingents' => ['Bangkalan A', 'Kab Banyuwangi', 'Kab Jombang', 'Kota Kediri', 'Kab Pasuruan', 'Surabaya A', 'Surabaya B']],
                    ['match_id' => 'RB15', 'name' => 'Randori 50Kg', 'contingents' => ['Bangkalan B', 'Kab Jember', 'Kab Banyuwangi', 'Kab Jombang', 'Kab Pasuruan', 'Surabaya A']],
                    ['match_id' => 'RB16', 'name' => 'Randori 55Kg', 'contingents' => ['Kab Jember', 'Kab Banyuwangi', 'Kota Malang', 'Kab Jombang', 'Kota Kediri', 'Kab Pasuruan', 'Surabaya B']],
                    ['match_id' => 'RB17', 'name' => 'Randori 70Kg eksebisi', 'contingents' => ['Bangkalan B', 'Surabaya B', 'Surabaya A']],
                ],
                'Campuran' => [
                    ['match_id' => 'RB18', 'name' => 'Embu Pasangan Kyu kenshi eksebisi', 'contingents' => ['Surabaya A', 'Kota Malang', 'Kab Jombang', 'Surabaya A']],
                    ['match_id' => 'RB19', 'name' => 'Embu Beregu putra/putri/campuran eksebisi', 'contingents' => ['Kota Malang', 'Kab Jombang', 'kab jombang', 'Kota Malang', 'Kab Jombang']],
                ],
            ],
            'Dewasa' => [
                'Putra' => [
                    ['match_id' => 'D1', 'name' => 'embu tandoku kyu 3/2 eksebisi', 'contingents' => ['Bangkalan A', 'Kab Jember', 'Kota Malang', 'Kab Jember']],
                    ['match_id' => 'D2', 'name' => 'embu tandoku kyu 1', 'contingents' => ['Bangkalan A', 'Kab Jember', 'Kab Jombang', 'Surabaya A']],
                    ['match_id' => 'D3', 'name' => 'Randori 50Kg eksebisi', 'contingents' => ['Bangkalan A', 'Kab Jember', 'Kota Kediri']],
                    ['match_id' => 'D4', 'name' => 'Randori 55Kg eksebisi', 'contingents' => ['Kota Kediri', 'Kab Pasuruan', 'kota malang']],
                    ['match_id' => 'D5', 'name' => 'Randori 60Kg eksebisi', 'contingents' => ['Bangkalan A', 'Kab Jember', 'Kota Malang', 'Kab Jombang', 'Kota Kediri', 'Kab Pasuruan', 'Kota malang 3', 'kota kediri']],
                    ['match_id' => 'D6', 'name' => 'Randori 65Kg', 'contingents' => ['Kota Malang', 'Kab Jombang', 'Surabaya A', 'kota kediri']],
                    ['match_id' => 'D7', 'name' => 'Randori 70Kg eksebisi', 'contingents' => ['Kota Kediri', 'kota kediri', 'Bangkalan A', 'Kab Jember']],
                ],
                'Putri' => [
                    ['match_id' => 'D8', 'name' => 'embu tandoku kyu 3/2 eksebisi', 'contingents' => ['Kab Banyuwangi', 'Kab Jember', 'Kab Banyuwangi', 'Kab Pasuruan']],
                    ['match_id' => 'D9', 'name' => 'embu tandoku kyu 1', 'contingents' => ['Kab Jember', 'Kab Banyuwangi', 'Kota Malang', 'Kab Jombang', 'Surabaya A']],
                    ['match_id' => 'D10', 'name' => 'Randori 45Kg', 'contingents' => ['Bangkalan A', 'Kab Banyuwangi', 'Kab Jombang', 'Kota Kediri', 'Kab Pasuruan']],
                    ['match_id' => 'D11', 'name' => 'Randori 50Kg', 'contingents' => ['Bangkalan B', 'Kab Jember', 'Kab Banyuwangi', 'Kab Jombang', 'Kab Pasuruan', 'Surabaya A']],
                    ['match_id' => 'D12', 'name' => 'Randori 55Kg', 'contingents' => ['Kab Jember', 'Kab Banyuwangi', 'Kota Malang', 'Kab Jombang', 'Kab Pasuruan', 'surabaya A']],
                    ['match_id' => 'D13', 'name' => 'Randori 60Kg', 'contingents' => ['Kab Jember', 'Kab Banyuwangi', 'Kota Malang', 'surabaya A']],
                    ['match_id' => 'D14', 'name' => 'Randori 65Kg', 'contingents' => ['Kab Banyuwangi', 'surabaya b', 'surabaya c', 'surabaya A']],
                ],
                'Campuran' => [
                    ['match_id' => 'D15', 'name' => 'Embu Pasangan Kyu kenshi putra/putri/campuran', 'contingents' => ['Bangkalan A', 'Surabaya A', 'kab banyuwangi', 'kota malang', 'surabaya B', 'kab jombang']],
                    ['match_id' => 'D16', 'name' => 'Embu tandoku yudansha putra/putri eksebisi', 'contingents' => ['kota malang', 'kota malang', 'surabaya A', 'kota malang', 'surabaya B', 'surabaya C']],
                ],
            ],
        ];
    }
}
