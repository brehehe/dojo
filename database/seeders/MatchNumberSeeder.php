<?php

namespace Database\Seeders;

use App\Models\Group\AgeGroup;
use App\Models\MatchNumber\MatchNumber;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MatchNumberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET session_replication_role = \'replica\';');
        MatchNumber::truncate();
        AgeGroup::truncate();
        DB::statement('SET session_replication_role = \'origin\';');

        $data = [
            'Pemula' => [
                'price' => 400000,
                'Laki-laki' => ['Embu Tandoku Kyu 6 (Eksibisi)', 'Embu Pasangan Kyu 6 (Eksibisi)', 'Embu Tandoku Kyu 5-4', 'Embu Tandoku Kyu 3', 'Embu Tandoku Kyu 2', 'Embu Tandoku Kyu 1', 'Embu Pasangan Kyu 5-4', 'Embu Pasangan Kyu 3', 'Embu Pasangan Kyu 2', 'Embu Pasangan Kyu 1'],
                'Perempuan' => ['Embu Tandoku Kyu 6 (Eksibisi)', 'Embu Pasangan Kyu 6 (Eksibisi)', 'Embu Tandoku Kyu 5-4', 'Embu Tandoku Kyu 3', 'Embu Tandoku Kyu 2', 'Embu Tandoku Kyu 1', 'Embu Pasangan Kyu 5-4', 'Embu Pasangan Kyu 3', 'Embu Pasangan Kyu 2', 'Embu Pasangan Kyu 1'],
            ],
            'Remaja A' => [
                'price' => 500000,
                'Laki-laki' => ['Embu Tandoku Kyu 5-4', 'Embu Tandoku Kyu 3', 'Embu Tandoku Kyu 2', 'Embu Tandoku Kyu 1', 'Embu Pasangan Kyu 5-4', 'Embu Pasangan Kyu 3', 'Embu Pasangan Kyu 2', 'Embu Pasangan Kyu 1', 'Embu Beregu'],
                'Perempuan' => ['Embu Tandoku Kyu 5-4', 'Embu Tandoku Kyu 3', 'Embu Tandoku Kyu 2', 'Embu Tandoku Kyu 1', 'Embu Pasangan Kyu 5-4', 'Embu Pasangan Kyu 3', 'Embu Pasangan Kyu 2', 'Embu Pasangan Kyu 1', 'Embu Beregu'],
            ],
            'Remaja B' => [
                'price' => 500000,
                'Laki-laki' => ['Embu Tandoku Kyu 5-4', 'Embu Tandoku Kyu 3', 'Embu Tandoku Kyu 2', 'Embu Tandoku Kyu 1', 'Embu Pasangan Kyu 5-4', 'Embu Pasangan Kyu 3', 'Embu Pasangan Kyu 2', 'Embu Pasangan Kyu 1', 'Embu Beregu', 'Randori 45Kg', 'Randori 50Kg', 'Randori 55Kg', 'Randori 60Kg', 'Randori 65Kg', 'Randori 70Kg', 'Randori >70Kg'],
                'Perempuan' => ['Embu Tandoku Kyu 5-4', 'Embu Tandoku Kyu 3', 'Embu Tandoku Kyu 2', 'Embu Tandoku Kyu 1', 'Embu Pasangan Kyu 5-4', 'Embu Pasangan Kyu 3', 'Embu Pasangan Kyu 2', 'Embu Pasangan Kyu 1', 'Embu Beregu', 'Randori 45Kg', 'Randori 50Kg', 'Randori 55Kg', 'Randori 60Kg', 'Randori 65Kg', 'Randori 70Kg'],
            ],
            'Dewasa A' => [
                'price' => 500000,
                'Laki-laki' => ['Embu Tandoku Kyu 3', 'Embu Tandoku Kyu 2', 'Embu Tandoku Kyu 1', 'Embu Tandoku Yudansa', 'Embu Pasangan Kyu 3', 'Embu Pasangan Kyu 2', 'Embu Pasangan Kyu 1', 'Embu Beregu', 'Randori 50Kg', 'Randori 55Kg', 'Randori 60Kg', 'Randori 65Kg', 'Randori 70Kg', 'Randori >70Kg'],
                'Perempuan' => ['Embu Tandoku Kyu 3', 'Embu Tandoku Kyu 2', 'Embu Tandoku Kyu 1', 'Embu Tandoku Yudansa', 'Embu Pasangan Kyu 3', 'Embu Pasangan Kyu 2', 'Embu Pasangan Kyu 1', 'Embu Beregu', 'Randori 50Kg', 'Randori 55Kg', 'Randori 60Kg', 'Randori 65Kg', 'Randori 70Kg'],
            ],
            'Dewasa B (Senior)' => [
                'price' => 500000,
                'Laki-laki' => ['Embu Tandoku Kyu 3', 'Embu Tandoku Kyu 2', 'Embu Tandoku Kyu 1', 'Embu Tandoku Yudansa', 'Embu Pasangan Kyu 3', 'Embu Pasangan Kyu 2', 'Embu Pasangan Kyu 1', 'Embu Pasangan Yudansa', 'Embu Beregu', 'Randori 50Kg', 'Randori 55Kg', 'Randori 60Kg', 'Randori 65Kg', 'Randori 70Kg', 'Randori >70Kg'],
                'Perempuan' => ['Embu Tandoku Kyu 3', 'Embu Tandoku Kyu 2', 'Embu Tandoku Kyu 1', 'Embu Tandoku Yudansa', 'Embu Pasangan Kyu 3', 'Embu Pasangan Kyu 2', 'Embu Pasangan Kyu 1', 'Embu Pasangan Yudansa', 'Embu Beregu', 'Randori 50Kg', 'Randori 55Kg', 'Randori 60Kg', 'Randori 65Kg', 'Randori 70Kg'],
            ],
        ];

        $orderId = 1;

        foreach ($data as $ageGroupName => $groupData) {
            $ageGroup = AgeGroup::create([
                'name' => $ageGroupName,
                'price' => $groupData['price'],
                'order' => $orderId++,
            ]);

            foreach (['Laki-laki', 'Perempuan'] as $genderId) {
                if (! isset($groupData[$genderId])) {
                    continue;
                }

                foreach ($groupData[$genderId] as $matchName) {
                    $genderEnum = ($genderId === 'Laki-laki') ? 'Male' : 'Female';

                    if (str_contains(strtolower($matchName), 'randori')) {
                        $draftType = 'randori';
                        $maxAthletes = 1;
                    } else {
                        $draftType = 'embu';
                        if (str_contains(strtolower($matchName), 'beregu')) {
                            $maxAthletes = 4;
                        } elseif (str_contains(strtolower($matchName), 'pasangan')) {
                            $maxAthletes = 2;
                        } else {
                            $maxAthletes = 1;
                        }
                    }

                    MatchNumber::create([
                        'name' => $matchName,
                        'age_group_id' => $ageGroup->id,
                        'gender' => $genderEnum,
                        'draft_type' => $draftType,
                        'max_athletes' => $maxAthletes,
                        'order' => 0, // optional sorting for later
                    ]);
                }
            }
        }
    }
}
