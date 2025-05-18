<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;


class DatabaseSeeder extends Seeder
{

    public function run(): void
    {


        $users = User::factory(10)->create();

        User::factory(1)->create([
            'name' => 'Admin User',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]); User::factory(1)->create([
            'name' => 'Delivery User',
            'email' => 'delivery@delivery.com',
            'password' => bcrypt('password'),
            'role' => 'delivery',
        ]);

         $this->call([
            AllergiesSeeder::class,
            AllergyIngredientSeeder::class,
            IngredientsSeeder::class,
            UserAllergySeeder::class,
         ]);
    }
}
