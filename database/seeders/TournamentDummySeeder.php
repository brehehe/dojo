<?php

namespace Database\Seeders;

use App\Models\Athlete;
use App\Models\Contingent;
use App\Models\Group\AgeGroup;
use App\Models\Group\WeightGroup;
use App\Models\MatchNumber\MatchNumber;
use App\Models\Official;
use App\Models\Registration;
use App\Models\Technique\Technique;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TournamentDummySeeder extends Seeder
{
    /**
     * Total contingents to seed.
     */
    private int $totalContingents = 40;

    /**
     * Indonesian regency names for realistic data.
     */
    private array $kabKota = [
        'Kota Bandung', 'Kota Surabaya', 'Kota Medan', 'Kota Makassar', 'Kota Semarang',
        'Kota Yogyakarta', 'Kota Jakarta Pusat', 'Kota Palembang', 'Kota Balikpapan', 'Kota Manado',
        'Kab. Bogor', 'Kab. Bekasi', 'Kab. Tangerang', 'Kab. Sidoarjo', 'Kab. Malang',
        'Kab. Gresik', 'Kab. Kediri', 'Kab. Jember', 'Kab. Banyuwangi', 'Kab. Banyumas',
        'Kota Depok', 'Kota Bekasi', 'Kota Tangerang', 'Kota Pekanbaru', 'Kota Padang',
        'Kota Batam', 'Kota Pontianak', 'Kota Banjarmasin', 'Kota Samarinda', 'Kota Jayapura',
        'Kab. Cianjur', 'Kab. Garut', 'Kab. Tasikmalaya', 'Kab. Cirebon', 'Kab. Kuningan',
        'Kab. Karawang', 'Kab. Purwakarta', 'Kab. Subang', 'Kab. Sukabumi', 'Kab. Bandung',
    ];

    private array $dojoNames = [
        'Dojo Sakura', 'Dojo Garuda', 'Dojo Nusantara', 'Dojo Matahari', 'Dojo Harimau',
        'Dojo Rajawali', 'Dojo Singa', 'Dojo Gajah', 'Dojo Banteng', 'Dojo Kencana',
    ];

    private array $officialRoles = ['Pelatih', 'Manajer', 'Asisten Pelatih', 'Fisioterapis'];

    private array $bloodTypes = ['A', 'B', 'AB', 'O'];

    private array $kyuLevels = ['Kyu 6', 'Kyu 5', 'Kyu 4', 'Kyu 3', 'Kyu 2', 'Kyu 1'];

    private array $yudansaLevels = ['Dan 1', 'Dan 2'];

    public function run(): void
    {
        // Load master data
        $ageGroups   = AgeGroup::orderBy('order')->get()->keyBy('id');
        $weightGroups = WeightGroup::orderBy('order')->get();
        $techniques  = Technique::orderBy('order')->get();
        $techniqueIds = $techniques->pluck('id')->toArray();

        // Load all match numbers grouped for smart assignment
        $matchNumbers = MatchNumber::with('ageGroup')->get();

        // Group match numbers by [age_group_id][gender][draft_type]
        $matchMap = [];
        foreach ($matchNumbers as $mn) {
            $ag = $mn->age_group_id;
            $g  = $mn->gender;
            $t  = $mn->draft_type;
            $matchMap[$ag][$g][$t][] = $mn;
        }

        // Track how many seats taken per match_number (per contingent: max_athletes is per-contingent limit)
        // We'll use a per-contingent tracker per match_number
        $contingentMatchCount = []; // [$contingentId][$matchNumberId] = count

        $this->command->info("Seeding {$this->totalContingents} contingents with full registrations...");
        $bar = $this->command->getOutput()->createProgressBar($this->totalContingents);
        $bar->start();

        DB::transaction(function () use (
            $ageGroups, $weightGroups, $techniques, $techniqueIds,
            $matchMap, &$contingentMatchCount, $bar
        ) {
            for ($c = 1; $c <= $this->totalContingents; $c++) {
                $kabKota   = $this->kabKota[($c - 1) % count($this->kabKota)];
                $shortCity = Str::of($kabKota)->after('. ')->before(' ')->toString() ?: "Kota{$c}";
                $contingentName = "Kontingen {$shortCity} " . chr(64 + (($c - 1) % 26 + 1));

                // 1. Create User
                $email = "kontingen{$c}@dummy.test";
                $user  = User::firstOrCreate(
                    ['email' => $email],
                    [
                        'name'     => $contingentName,
                        'password' => Hash::make('password'),
                    ]
                );
                $user->assignRole('Contingent');

                // 2. Create Contingent
                $contingent = Contingent::create([
                    'user_id'      => $user->id,
                    'name'         => $contingentName,
                    'kab_kota'     => $kabKota,
                    'leader_name'  => fake()->name(),
                    'leader_phone' => '08' . fake()->numerify('##########'),
                    'email'        => $email,
                    'address'      => fake()->address(),
                ]);

                // 3. Create Registration
                $uniqueCode  = rand(100, 999);
                $totalCost   = 2500000; // contingent fee base
                $registration = Registration::create([
                    'contingent_id'      => $contingent->id,
                    'total_cost'         => $totalCost,
                    'final_amount'       => $totalCost + $uniqueCode,
                    'unique_code'        => $uniqueCode,
                    'payment_method'     => 'BCA',
                    'referral_code'      => 'KEMPO-' . strtoupper(Str::random(5)),
                    'status'             => rand(0,1) == 1 ? 'verified' : 'pending',
                    'sim_perkemi_confirm' => 'Ya',
                ]);

                // 4. Create Officials (1-2 per contingent)
                $officialCount = rand(1, 2);
                for ($o = 0; $o < $officialCount; $o++) {
                    $official = Official::create([
                        'contingent_id' => $contingent->id,
                        'name'          => fake()->name(),
                        'role'          => $this->officialRoles[$o % count($this->officialRoles)],
                        'phone'         => '08' . fake()->numerify('##########'),
                    ]);
                    $registration->officials()->attach($official->id, [
                        'role'       => $official->role,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                // 5. Assign athletes to match numbers
                // Strategy: tiap kontingen mendaftar di beberapa age group
                // Pilih 2-3 age group secara bergiliran
                $ageGroupPool = $ageGroups->values()->toArray();
                // Rotate which age groups this contingent focuses on
                $startAg = ($c - 1) % count($ageGroupPool);
                $selectedAgIds = [
                    $ageGroupPool[$startAg]['id'],
                    $ageGroupPool[($startAg + 1) % count($ageGroupPool)]['id'],
                ];

                $contingentMatchCount[$contingent->id] = [];

                foreach ($selectedAgIds as $agId) {
                    $ageGroup = $ageGroups[$agId];

                    // Male and Female athletes
                    foreach (['Male', 'Female'] as $gender) {
                        $embuMatches   = $matchMap[$agId][$gender]['embu']   ?? [];
                        $mixEmbu       = $matchMap[$agId]['Mix']['embu']      ?? [];
                        $randoriMatches = $matchMap[$agId][$gender]['randori'] ?? [];
                        $allEmbu       = array_merge($embuMatches, $mixEmbu);

                        // ─── EMBU ATHLETES ────────────────────────────────────────

                        // Tandoku embu (1 athlete per match)
                        $tandokuEmbu = array_filter($allEmbu, fn ($m) => $m->max_athletes === 1);
                        // Pick up to 3 tandoku slots
                        $tandokuSlice = array_slice(array_values($tandokuEmbu), 0, 3);

                        foreach ($tandokuSlice as $mn) {
                            $taken = $contingentMatchCount[$contingent->id][$mn->id] ?? 0;
                            if ($taken >= $mn->max_athletes) {
                                continue;
                            }
                            $athlete = $this->createAthlete($gender, $ageGroup, $weightGroups);
                            $this->attachAthleteToRegistration($registration, $contingent, $athlete, $ageGroup, $weightGroups, $mn, $techniqueIds, $contingentMatchCount);
                        }

                        // Pasangan embu (2 athletes per match, need 2 athletes)
                        $pasanganEmbu = array_filter($allEmbu, fn ($m) => $m->max_athletes === 2);
                        $pasanganSlice = array_slice(array_values($pasanganEmbu), 0, 2);

                        foreach ($pasanganSlice as $mn) {
                            $taken = $contingentMatchCount[$contingent->id][$mn->id] ?? 0;
                            $slotsLeft = $mn->max_athletes - $taken;
                            if ($slotsLeft <= 0) {
                                continue;
                            }
                            $toCreate = min($slotsLeft, $mn->max_athletes); // should be 2
                            for ($a = 0; $a < $toCreate; $a++) {
                                $athlete = $this->createAthlete($gender, $ageGroup, $weightGroups);
                                $this->attachAthleteToRegistration($registration, $contingent, $athlete, $ageGroup, $weightGroups, $mn, $techniqueIds, $contingentMatchCount);
                            }
                        }

                        // Beregu embu (4 athletes per match)
                        $beriguEmbu = array_filter($allEmbu, fn ($m) => $m->max_athletes === 4);
                        $beregSlice = array_slice(array_values($beriguEmbu), 0, 1);

                        foreach ($beregSlice as $mn) {
                            $taken = $contingentMatchCount[$contingent->id][$mn->id] ?? 0;
                            $slotsLeft = $mn->max_athletes - $taken;
                            if ($slotsLeft <= 0) {
                                continue;
                            }
                            for ($a = 0; $a < $slotsLeft; $a++) {
                                $athlete = $this->createAthlete($gender, $ageGroup, $weightGroups);
                                $this->attachAthleteToRegistration($registration, $contingent, $athlete, $ageGroup, $weightGroups, $mn, $techniqueIds, $contingentMatchCount);
                            }
                        }

                        // ─── RANDORI ATHLETES ─────────────────────────────────────
                        // Each randori match = 1 athlete per contingent
                        // Register in 2-4 randori matches (different weight classes)
                        $randoriSlice = array_slice($randoriMatches, 0, rand(2, 4));
                        foreach ($randoriSlice as $mn) {
                            $taken = $contingentMatchCount[$contingent->id][$mn->id] ?? 0;
                            if ($taken >= $mn->max_athletes) {
                                continue;
                            }
                            // Derive weight from match name
                            $weight = $this->deriveWeightFromMatch($mn->name);
                            $athlete = $this->createAthlete($gender, $ageGroup, $weightGroups, $weight);
                            $this->attachAthleteToRegistration($registration, $contingent, $athlete, $ageGroup, $weightGroups, $mn, [], $contingentMatchCount);
                        }
                    }
                }

                // Update total cost properly
                $athleteCount = DB::table('registration_athlete')
                    ->where('registration_id', $registration->id)
                    ->count();
                $totalAthleteFee = $athleteCount * ($ageGroups->has($selectedAgIds[0]) ? (int) $ageGroups[$selectedAgIds[0]]->price : 400000);
                $total = 2500000 + $totalAthleteFee;
                $registration->update([
                    'total_cost'    => $total,
                    'final_amount'  => $total + $uniqueCode,
                ]);

                $bar->advance();
            }
        });

        $bar->finish();
        $this->command->newLine();
        $this->command->info('✅ Done! Seeded ' . $this->totalContingents . ' contingents with athletes and registrations.');
    }

    // ─── HELPERS ────────────────────────────────────────────────────────────

    /**
     * Create a new Athlete record with randomized data.
     */
    private function createAthlete(
        string $gender,
        mixed $ageGroup,
        mixed $weightGroups,
        ?int $fixedWeight = null
    ): Athlete {
        $ageName = is_array($ageGroup) ? $ageGroup['name'] : $ageGroup->name;

        // Birth date range by age group
        $birthDateRange = match (true) {
            str_contains($ageName, 'Pemula')  => [14, 17],
            str_contains($ageName, 'Remaja A') => [15, 18],
            str_contains($ageName, 'Remaja B') => [17, 21],
            str_contains($ageName, 'Dewasa A') => [19, 28],
            default                            => [25, 40],
        };

        $birthYear = now()->year - rand(...$birthDateRange);
        $birthDate = now()->setYear($birthYear)->setMonth(rand(1, 12))->setDay(rand(1, 28));

        // Rank by age group
        $pemula_kyu = ['Kyu 6', 'Kyu 5', 'Kyu 4'];
        $rank = match (true) {
            str_contains($ageName, 'Pemula')  => $pemula_kyu[array_rand($pemula_kyu)],
            str_contains($ageName, 'Remaja')  => $this->kyuLevels[rand(1, 4)],
            str_contains($ageName, 'Dewasa A') => $this->kyuLevels[rand(2, 5)],
            default                            => $this->yudansaLevels[array_rand($this->yudansaLevels)],
        };

        $weight = $fixedWeight ?? rand(40, 80);

        // Pick weight group
        $wg = $weightGroups->first() ?? null;
        foreach ($weightGroups as $wgItem) {
            if ($wgItem->min_weight && $weight >= $wgItem->min_weight && $weight <= ($wgItem->max_weight ?? 999)) {
                $wg = $wgItem;
                break;
            }
        }
        $wgId = $wg?->id ?? $weightGroups->first()?->id;

        $nik = $this->generateUniqueNik();

        return Athlete::create([
            'nik'           => $nik,
            'name'          => fake('id_ID')->name($gender === 'Male' ? 'male' : 'female'),
            'gender'        => $gender,
            'birth_place'   => fake('id_ID')->city(),
            'blood_type'    => $this->bloodTypes[array_rand($this->bloodTypes)],
            'birth_date'    => $birthDate->format('Y-m-d'),
            'address'       => fake('id_ID')->address(),
            'phone'         => '08' . fake()->numerify('##########'),
            'bpjs_number'   => fake()->numerify('##############'),
            'bpjs_status'   => 'Aktif',
        ]);
    }

    /**
     * Attach athlete to registration with all pivot data, and link to match number.
     */
    private function attachAthleteToRegistration(
        Registration $registration,
        Contingent $contingent,
        Athlete $athlete,
        mixed $ageGroup,
        mixed $weightGroups,
        MatchNumber $matchNumber,
        array $techniqueIds,
        array &$contingentMatchCount
    ): void {
        $ageName = is_array($ageGroup) ? $ageGroup['name'] : $ageGroup->name;
        $ageGroupId = is_array($ageGroup) ? $ageGroup['id'] : $ageGroup->id;

        $weight = rand(40, 80);
        $wg = $weightGroups->first();
        foreach ($weightGroups as $wgItem) {
            if ($wgItem->min_weight && $weight >= $wgItem->min_weight && $weight <= ($wgItem->max_weight ?? 999)) {
                $wg = $wgItem;
                break;
            }
        }

        // Attach to athlete_contingent (primary membership)
        $currentPrimary = $athlete->contingents()->wherePivot('is_primary', true)->first();
        if (! $currentPrimary || $currentPrimary->id !== $contingent->id) {
            if ($currentPrimary) {
                $athlete->contingents()->updateExistingPivot($currentPrimary->id, ['is_primary' => false]);
            }
            $athlete->contingents()->syncWithoutDetaching([
                $contingent->id => [
                    'is_primary' => true,
                    'joined_at'  => now(),
                ],
            ]);
        }

        // Attach to registration (athlete_registration pivot)
        // Check if already attached
        $alreadyInRegistration = DB::table('registration_athlete')
            ->where('registration_id', $registration->id)
            ->where('athlete_id', $athlete->id)
            ->exists();

        if (! $alreadyInRegistration) {
            $registration->athletes()->attach($athlete->id, [
                'weight'          => $weight,
                'weight_group_id' => $wg?->id,
                'kyu'             => 'Kyu 3',
                'age_group'       => $ageName,
                'rank'            => 'Kyu 3',
                'dojo_origin'     => $this->dojoNames[array_rand($this->dojoNames)],
                'city'            => fake('id_ID')->city(),
                'match_type'      => $matchNumber->draft_type === 'randori' ? 'Randori' : 'Embu',
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);
        }

        // Select techniques (only for embu; pick 2-5 random techniques)
        $selectedTechniqueIds = [];
        if ($matchNumber->draft_type === 'embu' && ! empty($techniqueIds)) {
            $count = min(6, count($techniqueIds));
            $shuffled = $techniqueIds;
            shuffle($shuffled);
            $selectedTechniqueIds = array_slice($shuffled, 0, $count);
        }

        // Attach to athlete_match_number (only once per athlete+match+registration)
        $alreadyInMatch = DB::table('athlete_match_number')
            ->where('athlete_id', $athlete->id)
            ->where('match_number_id', $matchNumber->id)
            ->where('registration_id', $registration->id)
            ->exists();

        if (! $alreadyInMatch) {
            DB::table('athlete_match_number')->insert([
                'athlete_id'      => $athlete->id,
                'match_number_id' => $matchNumber->id,
                'registration_id' => $registration->id,
                'technique_ids'   => json_encode($selectedTechniqueIds),
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);

            // Update tracker
            $contingentMatchCount[$contingent->id][$matchNumber->id] =
                ($contingentMatchCount[$contingent->id][$matchNumber->id] ?? 0) + 1;
        }
    }

    /**
     * Derive a weight value (in kg) from a randori match name.
     */
    private function deriveWeightFromMatch(string $name): int
    {
        if (str_contains($name, '>70') || str_contains($name, '+70')) {
            return rand(72, 90);
        }
        if (preg_match('/(\d+)Kg/i', $name, $m)) {
            $kg = (int) $m[1];
            return rand(max(40, $kg - 4), $kg);
        }
        return rand(50, 70);
    }

    /**
     * Generate a unique 16-digit NIK that doesn't exist yet in the athletes table.
     */
    private function generateUniqueNik(): string
    {
        do {
            $nik = fake()->numerify('################');
        } while (Athlete::where('nik', $nik)->exists());

        return $nik;
    }
}
