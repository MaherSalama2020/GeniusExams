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
        // $this->call(UserSeeder::class);
        $this->call([
            UsersTableSeeder::class,
            CertificatesTableSeeder::class,
            ExamsTableSeeder::class,
            QuestionsTableSeeder::class,
            OptionsTableSeeder::class,
            CouponsTableSeeder::class,
            ReviewsTableSeeder::class,
            ]);
    }
}
