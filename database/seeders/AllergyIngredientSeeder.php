<?php

namespace Database\Seeders;

use App\Models\allergy;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Ingredient;

class AllergyIngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $allergyIngredientMap = [
            'Lactose Intolerance' => ['Milk'],
            'Egg Allergy' => ['Eggs'],
            'Peanut Allergy' => ['Peanuts'],
            'Soy Allergy' => ['Soy'],
            'Gluten Sensitivity' => ['Wheat'],
            'Seafood Allergy' => ['Fish', 'Shellfish'],
            'Nut Allergy' => ['Tree Nuts'],
        ];

        foreach ($allergyIngredientMap as $allergyName => $ingredientNames) {
            $allergy = allergy::where('name', $allergyName)->first();
            $ingredientIds = Ingredient::whereIn('name', $ingredientNames)->pluck('id');
            if ($allergy) {
                $allergy->ingredients()->attach($ingredientIds);
            }
        }
    }
}
