<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            // PEMULA (Festival/Beginner)
            ['name' => 'Kata Perorangan Pra Usia Dini Putra', 'type' => 'Kata', 'gender' => 'Male', 'age_group' => 'Pra Usia Dini', 'match_type' => 'Pemula'],
            ['name' => 'Kata Perorangan Pra Usia Dini Putri', 'type' => 'Kata', 'gender' => 'Female', 'age_group' => 'Pra Usia Dini', 'match_type' => 'Pemula'],
            ['name' => 'Kata Perorangan Usia Dini Putra', 'type' => 'Kata', 'gender' => 'Male', 'age_group' => 'Usia Dini', 'match_type' => 'Pemula'],
            ['name' => 'Kata Perorangan Usia Dini Putri', 'type' => 'Kata', 'gender' => 'Female', 'age_group' => 'Usia Dini', 'match_type' => 'Pemula'],
            
            // REMAJA (Cadet/Junior)
            ['name' => 'Kata Perorangan Cadet Putra', 'type' => 'Kata', 'gender' => 'Male', 'age_group' => 'Cadet', 'match_type' => 'Remaja'],
            ['name' => 'Kata Perorangan Cadet Putri', 'type' => 'Kata', 'gender' => 'Female', 'age_group' => 'Cadet', 'match_type' => 'Remaja'],
            ['name' => 'Kata Perorangan Junior Putra', 'type' => 'Kata', 'gender' => 'Male', 'age_group' => 'Junior', 'match_type' => 'Remaja'],
            ['name' => 'Kata Perorangan Junior Putri', 'type' => 'Kata', 'gender' => 'Female', 'age_group' => 'Junior', 'match_type' => 'Remaja'],

            // DEWASA (Senior/Under 21)
            ['name' => 'Kata Perorangan Under 21 Putra', 'type' => 'Kata', 'gender' => 'Male', 'age_group' => 'Under 21', 'match_type' => 'Dewasa'],
            ['name' => 'Kata Perorangan Senior Putra', 'type' => 'Kata', 'gender' => 'Male', 'age_group' => 'Senior', 'match_type' => 'Dewasa'],
            
            // KUMITE Examples
            ['name' => 'Kumite -30kg Usia Dini Putra', 'type' => 'Kumite', 'gender' => 'Male', 'age_group' => 'Usia Dini', 'weight_class' => '-30kg', 'match_type' => 'Pemula'],
            ['name' => 'Kumite -35kg Pra Pemula Putra', 'type' => 'Kumite', 'gender' => 'Male', 'age_group' => 'Pra Pemula', 'weight_class' => '-35kg', 'match_type' => 'Pemula'],
            ['name' => 'Kumite Senior Putra -60kg', 'type' => 'Kumite', 'gender' => 'Male', 'age_group' => 'Senior', 'weight_class' => '-60kg', 'match_type' => 'Dewasa'],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(['name' => $category['name']], $category);
        }
    }
}
