<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->truncate();

        $categoriesInput = [
            'Pemula' => [
                'Laki-laki' => ['Embu Tandoku Kyu 6 (Eksibisi)', 'Embu Pasangan Kyu 6 (Eksibisi)', 'Embu Tandoku Kyu 5-4', 'Embu Tandoku Kyu 3', 'Embu Tandoku Kyu 2', 'Embu Tandoku Kyu 1', 'Embu Pasangan Kyu 5-4', 'Embu Pasangan Kyu 3', 'Embu Pasangan Kyu 2', 'Embu Pasangan Kyu 1'],
                'Perempuan' => ['Embu Tandoku Kyu 6 (Eksibisi)', 'Embu Pasangan Kyu 6 (Eksibisi)', 'Embu Tandoku Kyu 5-4', 'Embu Tandoku Kyu 3', 'Embu Tandoku Kyu 2', 'Embu Tandoku Kyu 1', 'Embu Pasangan Kyu 5-4', 'Embu Pasangan Kyu 3', 'Embu Pasangan Kyu 2', 'Embu Pasangan Kyu 1'],
            ],
            'Remaja A' => [
                'Laki-laki' => ['Embu Tandoku Kyu 5-4', 'Embu Tandoku Kyu 3', 'Embu Tandoku Kyu 2', 'Embu Tandoku Kyu 1', 'Embu Pasangan Kyu 5-4', 'Embu Pasangan Kyu 3', 'Embu Pasangan Kyu 2', 'Embu Pasangan Kyu 1', 'Embu Beregu'],
                'Perempuan' => ['Embu Tandoku Kyu 5-4', 'Embu Tandoku Kyu 3', 'Embu Tandoku Kyu 2', 'Embu Tandoku Kyu 1', 'Embu Pasangan Kyu 5-4', 'Embu Pasangan Kyu 3', 'Embu Pasangan Kyu 2', 'Embu Pasangan Kyu 1', 'Embu Beregu'],
            ],
            'Remaja B' => [
                'Laki-laki' => ['Embu Tandoku Kyu 5-4', 'Embu Tandoku Kyu 3', 'Embu Tandoku Kyu 2', 'Embu Tandoku Kyu 1', 'Embu Pasangan Kyu 5-4', 'Embu Pasangan Kyu 3', 'Embu Pasangan Kyu 2', 'Embu Pasangan Kyu 1', 'Embu Beregu', 'Randori 45Kg', 'Randori 50Kg', 'Randori 55Kg', 'Randori 60Kg', 'Randori 65Kg', 'Randori 70Kg', 'Randori >70Kg'],
                'Perempuan' => ['Embu Tandoku Kyu 5-4', 'Embu Tandoku Kyu 3', 'Embu Tandoku Kyu 2', 'Embu Tandoku Kyu 1', 'Embu Pasangan Kyu 5-4', 'Embu Pasangan Kyu 3', 'Embu Pasangan Kyu 2', 'Embu Pasangan Kyu 1', 'Embu Beregu', 'Randori 45Kg', 'Randori 50Kg', 'Randori 55Kg', 'Randori 60Kg', 'Randori 65Kg', 'Randori 70Kg'],
            ],
            'Dewasa' => [
                'Laki-laki' => ['Embu Tandoku Kyu 3', 'Embu Tandoku Kyu 2', 'Embu Tandoku Kyu 1', 'Embu Tandoku Yudansa', 'Embu Pasangan Kyu 3', 'Embu Pasangan Kyu 2', 'Embu Pasangan Kyu 1', 'Embu Beregu', 'Randori 50Kg', 'Randori 55Kg', 'Randori 60Kg', 'Randori 65Kg', 'Randori 70Kg', 'Randori >70Kg'],
                'Perempuan' => ['Embu Tandoku Kyu 3', 'Embu Tandoku Kyu 2', 'Embu Tandoku Kyu 1', 'Embu Tandoku Yudansa', 'Embu Pasangan Kyu 3', 'Embu Pasangan Kyu 2', 'Embu Pasangan Kyu 1', 'Embu Beregu', 'Randori 50Kg', 'Randori 55Kg', 'Randori 60Kg', 'Randori 65Kg', 'Randori 70Kg'],
            ],
        ];

        foreach ($categoriesInput as $ageGroup => $genders) {
            foreach ($genders as $genderCode => $events) {
                // Determine gender for DB mapping
                $gender = ($genderCode == 'Laki-laki') ? 'Male' : 'Female';

                foreach ($events as $eventName) {
                    // Map Embu -> Kata, Randori -> Kumite to pass DB enum contraints
                    $type = str_contains(strtolower($eventName), 'randori') ? 'Kumite' : 'Kata';

                    Category::create([
                        'name' => $eventName,
                        'type' => $type,
                        'gender' => $gender,
                        'age_group' => $ageGroup,
                        'match_type' => 'Kempo',
                    ]);
                }
            }
        }
    }
}
