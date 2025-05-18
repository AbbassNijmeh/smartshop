<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class IngredientsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ingredients = [
            ['name' => 'Milk'],
            ['name' => 'Eggs'],
            ['name' => 'Peanuts'],
            ['name' => 'Soy'],
            ['name' => 'Wheat'],
            ['name' => 'Fish'],
            ['name' => 'Shellfish'],
            ['name' => 'Tree Nuts'],
        ];

        Ingredient::insert($ingredients);
    }
}
