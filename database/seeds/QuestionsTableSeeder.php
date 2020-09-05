<?php

use Illuminate\Database\Seeder;

class QuestionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //.
        $questions = [
            [
                'name' => "Question 1",
            ],
            [
                'name' => "Question 2",
            ],
            [
                'name' => "Question 3",
            ],
            [
                'name' => "Question 4",
            ],
            [
                'name' => "Question 5",
            ],
            [
                'name' => "Question 6",
            ],
            [
                'name' => "Question 7",
            ],
        ];
        DB::table('questions')->insert($questions);
    }
}
