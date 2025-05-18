<?php

namespace Database\Seeders;

use App\Models\allergy;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AllergiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $allergies = [
            ['name' => 'Lactose Intolerance'],
            ['name' => 'Egg Allergy'],
            ['name' => 'Peanut Allergy'],
            ['name' => 'Soy Allergy'],
            ['name' => 'Gluten Sensitivity'],
            ['name' => 'Seafood Allergy'],
            ['name' => 'Nut Allergy'],
        ];

        allergy::insert($allergies);
    }
}
