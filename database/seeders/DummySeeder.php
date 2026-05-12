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

class DummySeeder extends Seeder
{
    public function run(): void
    {
        // 1. Clear existing dynamic data to start fresh
        DB::statement('SET session_replication_role = \'replica\';');
        DB::table('athlete_match_number')->truncate();
        DB::table('registration_athlete')->truncate();
        DB::table('registration_official')->truncate();
        DB::table('athlete_contingent')->truncate();
        DB::table('courts')->truncate();
        Registration::truncate();
        Athlete::truncate();
        Contingent::truncate();
        User::role('Contingent')->delete();
        
        // Clear master data to ensure fresh seeding
        WeightGroup::truncate();
        AgeGroup::truncate();
        MatchNumber::truncate();
        
        DB::statement('SET session_replication_role = \'origin\';');

        for ($i = 1; $i <= 3; $i++) {
            Court::create([
                'name' => 'Court '.$i,
                'order' => $i,
            ]);
        }

        $this->command->info('Seeding WeightGroup master data...');
        $weights = [
            ['name' => '45Kg', 'order' => 1],
            ['name' => '50Kg', 'order' => 2],
            ['name' => '55Kg', 'order' => 3],
            ['name' => '60Kg', 'order' => 4],
            ['name' => '65Kg', 'order' => 5],
            ['name' => '70Kg', 'order' => 6],
            ['name' => '75Kg', 'order' => 7],
            ['name' => '>75Kg', 'order' => 8],
        ];

        foreach ($weights as $w) {
            WeightGroup::create($w);
        }

        $this->command->info('Seeding AgeGroup master data...');
        $ages = [
            ['name' => 'Pemula', 'price' => 400000, 'order' => 1],
            ['name' => 'Remaja A', 'price' => 500000, 'order' => 2],
            ['name' => 'Remaja B', 'price' => 500000, 'order' => 3],
            ['name' => 'Dewasa', 'price' => 500000, 'order' => 4],
        ];

        foreach ($ages as $a) {
            AgeGroup::create($a);
        }

        $ageGroups = AgeGroup::all()->keyBy('name');

        // Ensure "Dewasa" mapping
        if ($ageGroups->has('Dewasa A') && ! $ageGroups->has('Dewasa')) {
            $ageGroups->put('Dewasa', $ageGroups->get('Dewasa A'));
        }

        // 3. Define the Tournament Data
        $tournamentData = $this->getTournamentData();

        $this->command->info('Seeding MatchNumber master data (Nomor Pertandingan)...');

        foreach ($tournamentData as $ageName => $sections) {
            $ageGroup = $ageGroups->get($ageName);
            
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

                    foreach ($targetGenders as $g) {
                        $draftType = str_contains($lowerName, 'randori') ? 'randori' : 'embu';
                        $maxAthletes = 1;
                        if (str_contains($lowerName, 'pasangan')) {
                            $maxAthletes = 2;
                        }
                        if (str_contains($lowerName, 'beregu')) {
                            $maxAthletes = 4;
                        }

                        // Clean up name and append gender suffix if splitting
                        $cleanName = $matchName;
                        if (count($targetGenders) > 1) {
                            $genderSuffix = match($g) {
                                'Male' => ' (Putra)',
                                'Female' => ' (Putri)',
                                'Mix' => ' (Campuran)',
                                default => ''
                            };
                            $cleanName = preg_replace('/\s*putra\/putri(\/campuran)?/i', '', $matchName) . $genderSuffix;
                        }

                        MatchNumber::create([
                            'name' => $cleanName,
                            'age_group_id' => $ageGroup->id,
                            'gender' => $g,
                            'match_id' => count($targetGenders) > 1 
                                ? ($matchInfo['match_id'] . '-' . substr($g, 0, 1)) 
                                : ($matchInfo['match_id'] ?? null),
                            'draft_type' => $draftType,
                            'max_athletes' => $maxAthletes,
                            'order' => 1,
                        ]);
                    }
                }
            }
        }

        $contingentsToSeed = [
            ['name' => 'Surabaya A', 'username' => 'surabayaa', 'password' => 'surabayaa'],
            ['name' => 'Surabaya B', 'username' => 'surabayab', 'password' => 'surabayab'],
            ['name' => 'Surabaya C', 'username' => 'surabayac', 'password' => 'surabayac'],
            ['name' => 'Surabaya D', 'username' => 'surabayad', 'password' => 'surabayad'],
            ['name' => 'Bangkalan A', 'username' => 'bangkalana', 'password' => 'bangkalana'],
            ['name' => 'Bangkalan B', 'username' => 'bangkalanb', 'password' => 'bangkalanb'],
            ['name' => 'Kota Malang 1', 'username' => 'malang1', 'password' => 'kotamalang1'],
            ['name' => 'Kota Malang 3', 'username' => 'malang3', 'password' => 'kotamalang3'],
            ['name' => 'Kota Kediri', 'username' => 'kediri', 'password' => 'kotakediri'],
            ['name' => 'Jombang', 'username' => 'jombang', 'password' => 'jombang'],
            ['name' => 'Banyuwangi', 'username' => 'banyuwangi', 'password' => 'banyuwangi'],
            ['name' => 'Sidoarjo', 'username' => 'sidoarjo', 'password' => 'sidoarjo'],
            ['name' => 'Jember', 'username' => 'jember', 'password' => 'jember'],
            ['name' => 'Gresik', 'username' => 'gresik', 'password' => 'gresik'],
            ['name' => 'Pasuruan', 'username' => 'pasuruan', 'password' => 'pasuruan'],
        ];

        foreach ($contingentsToSeed as $cData) {
            $cName = $cData['name'];
            $email = $cData['username'] . '@smart-perkemi.id';
            
            // Create User
            $user = User::create([
                'name' => $cName,
                'email' => $email,
                'password' => Hash::make($cData['password']),
            ]);

            $user->assignRole('Contingent');

            // Create Contingent
            Contingent::create([
                'user_id' => $user->id,
                'name' => $cName,
                'kab_kota' => $cName,
                'leader_name' => 'Ketua ' . $cName,
                'leader_phone' => null,
                'email' => null,
                'address' => null,
            ]);
        }

        $this->command->info('✅ Tournament master data (MatchNumbers & WeightGroups) seeded successfully!');
    }

    private function getTournamentData(): array
    {
        return [
            'Pemula' => [
                'Putra' => [
                    ['match_id' => 'P1', 'name' => 'Embu Tandoku kyu kenshi eksebisi'],
                ],
                'Putri' => [
                    ['match_id' => 'P2', 'name' => 'Embu Tandoku kyu kenshi'],
                ],
                'Campuran' => [
                    ['match_id' => 'P3', 'name' => 'Embu Pasangan campuran Kyu kenshi'],
                    ['match_id' => 'P4', 'name' => 'Embu Pasangan putra/putri Kyu kenshi eksebisi'],
                ],
            ],
            'Remaja A' => [
                'Putra' => [
                    ['match_id' => 'RA1', 'name' => 'embu tandoku kyu kenshi eksebisi'],
                ],
                'Putri' => [
                    ['match_id' => 'RA2', 'name' => 'Embu Tandoku Kyu kenshi eksebisi'],
                ],
                'Campuran' => [
                    ['match_id' => 'RA3', 'name' => 'Embu Pasangan Kyu kenshi Putra/Putri/Campuran eksebisi'],
                    ['match_id' => 'RA4', 'name' => 'Embu Beregu putra/putri/campuran eksebisi'],
                ],
            ],
            'Remaja B' => [
                'Putra' => [
                    ['match_id' => 'RB1', 'name' => 'embu tandoku kyu 4/3'],
                    ['match_id' => 'RB2', 'name' => 'embu tandoku kyu 2/1'],
                    ['match_id' => 'RB3', 'name' => 'embu pasangan kyu kenshi eksebisi'],
                    ['match_id' => 'RB4', 'name' => 'Randori 45Kg'],
                    ['match_id' => 'RB5', 'name' => 'Randori 50Kg'],
                    ['match_id' => 'RB6', 'name' => 'Randori 55Kg'],
                    ['match_id' => 'RB7', 'name' => 'Randori 60Kg'],
                    ['match_id' => 'RB8', 'name' => 'Randori 65Kg'],
                    ['match_id' => 'RB9', 'name' => 'Randori 70Kg'],
                    ['match_id' => 'RB10', 'name' => 'Randori >70Kg'],
                ],
                'Putri' => [
                    ['match_id' => 'RB11', 'name' => 'embu tandoku kyu 4/3'],
                    ['match_id' => 'RB12', 'name' => 'embu tandoku kyu 2/1'],
                    ['match_id' => 'RB13', 'name' => 'embu pasangan kyu Kenshi eksebisi'],
                    ['match_id' => 'RB14', 'name' => 'Randori 45Kg'],
                    ['match_id' => 'RB15', 'name' => 'Randori 50Kg'],
                    ['match_id' => 'RB16', 'name' => 'Randori 55Kg'],
                    ['match_id' => 'RB17', 'name' => 'Randori 70Kg eksebisi'],
                ],
                'Campuran' => [
                    ['match_id' => 'RB18', 'name' => 'Embu Pasangan Kyu kenshi eksebisi'],
                    ['match_id' => 'RB19', 'name' => 'Embu Beregu putra/putri/campuran eksebisi'],
                ],
            ],
            'Dewasa' => [
                'Putra' => [
                    ['match_id' => 'D1', 'name' => 'embu tandoku kyu 3/2 eksebisi'],
                    ['match_id' => 'D2', 'name' => 'embu tandoku kyu 1'],
                    ['match_id' => 'D3', 'name' => 'Randori 50Kg eksebisi'],
                    ['match_id' => 'D4', 'name' => 'Randori 55Kg eksebisi'],
                    ['match_id' => 'D5', 'name' => 'Randori 60Kg eksebisi'],
                    ['match_id' => 'D6', 'name' => 'Randori 65Kg'],
                    ['match_id' => 'D7', 'name' => 'Randori 70Kg eksebisi'],
                ],
                'Putri' => [
                    ['match_id' => 'D8', 'name' => 'embu tandoku kyu 3/2 eksebisi'],
                    ['match_id' => 'D9', 'name' => 'embu tandoku kyu 1'],
                    ['match_id' => 'D10', 'name' => 'Randori 45Kg'],
                    ['match_id' => 'D11', 'name' => 'Randori 50Kg'],
                    ['match_id' => 'D12', 'name' => 'Randori 55Kg'],
                    ['match_id' => 'D13', 'name' => 'Randori 60Kg'],
                    ['match_id' => 'D14', 'name' => 'Randori 65Kg'],
                ],
                'Campuran' => [
                    ['match_id' => 'D15', 'name' => 'Embu Pasangan Kyu kenshi putra/putri/campuran'],
                    ['match_id' => 'D16', 'name' => 'Embu tandoku yudansha putra/putri eksebisi'],
                ],
            ],
        ];
    }
}
