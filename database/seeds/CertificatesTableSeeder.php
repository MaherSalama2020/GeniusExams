<?php

use Illuminate\Database\Seeder;

class CertificatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $certificates=[
            [
                'name' => 'AI',
                'rate' => 1,
                'price' => 22.22,
                'available_for' => 180,
                'bookedNO' => 0,
                'ratedNO' => 0,
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                tempor incididunt ut labore et dolore magna aliqua consequat.',
                'image' => 'http://knowledgewavws.org/images/exam.jpeg',
                'created_at' => new DateTime,
                'updated_at' => null,
            ],
            [
                'name' => 'Management',
                'rate' => 2,
                'available_for' => 90,
                'price' => 33.33,
                'bookedNO' => 0,
                'ratedNO' => 0,
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                tempor incididunt ut labore et dolore magna aliqua consequat.',
                'image' => 'http://knowledgewavws.org/images/exam.jpeg',
                'created_at' => new DateTime,
                'updated_at' => null,
            ],
        ];
        DB::table('certificates')->insert($certificates);
    }
}
