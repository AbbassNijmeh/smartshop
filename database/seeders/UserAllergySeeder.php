<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\allergy;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserAllergySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::factory(10)->create();  // Adjust the number as needed

        // Get all allergies
        $allergies = allergy::all();

        // Assign random allergies to each user
        $users->each(function ($user) use ($allergies) {
            // Assign 1 to 3 random allergies to each user
            $user->allergies()->attach(
                $allergies->random(rand(1, 3))->pluck('id')->toArray()
            );
        });
    }
}
