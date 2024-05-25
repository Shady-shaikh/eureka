<?php

namespace Database\Seeders;

use App\Models\backend\Users;
use App\Models\frontend\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory(10)->create();
        // $faker = Faker::create();
        // foreach (range(1, 30) as $index) {
        //     Users::create([
        //         'email' => $faker->sentence(2),
        //         'fullname' => $faker->sentence(1),
        //         'username' => $faker->sentence(2),
        //         'password' => $faker->sentence(1)
        //     ]);
        // }
    }
}
