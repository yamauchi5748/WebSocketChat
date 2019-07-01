<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        DB::table('users')->delete();
        $faker = Faker\Factory::create('ja_JP');
        for ($i = 0; $i < 1000; $i++) {
            App\User::create([
                'name' => $faker->name . "\n",
                'email' => $faker->email,
                'password' => $faker->password . "\n" 
            ]);
        }
    }
}
