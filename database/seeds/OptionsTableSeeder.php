<?php

use Illuminate\Database\Seeder;

class OptionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $options = [
            // Question 1
            [
                'question_id' => 1,
                'name' => "Q1 O1 true",
                'sequence' => 1,
                'isCorrect'=>true,
                'explaination'=>'this is the explaination for correct answer for the question 1'
            ],
            [
                'question_id' => 1,
                'name' => "Q1 O2 false",
                'sequence' => 2,
                'isCorrect'=>false,
                'explaination'=>''
            ],
            [
                'question_id' => 1,
                'name' => "Q1 O3 false",
                'sequence' => 3,
                'isCorrect'=>false,
                'explaination'=>''
            ],
            [
                'question_id' => 1,
                'name' => "Q1 O4 false",
                'sequence' => 4,
                'isCorrect'=>false,
                'explaination'=>''
            ],
            // Question 2
            [
                'question_id' => 2,
                'name' => "Q2 O1 false",
                'sequence' => 1,
                'isCorrect'=>false,
                'explaination'=>''
            ],
            [
                'question_id' => 2,
                'name' => "Q2 O2 true",
                'sequence' => 2,
                'isCorrect'=>true,
                'explaination'=>'this is the explaination for correct answer for the question 2'
            ],
            [
                'question_id' => 2,
                'name' => "Q2 O3 false",
                'sequence' => 3,
                'isCorrect'=>false,
                'explaination'=>''
            ],
            [
                'question_id' => 2,
                'name' => "Q2 O4 false",
                'sequence' => 4,
                'isCorrect'=>false,
                'explaination'=>''
            ],
            // Question 3
            [
                'question_id' => 3,
                'name' => "Q3 O1 false",
                'sequence' => 1,
                'isCorrect'=>false,
                'explaination'=>''
            ],
            [
                'question_id' => 3,
                'name' => "Q3 O2 false",
                'sequence' => 2,
                'isCorrect'=>false,
                'explaination'=>''
            ],
            [
                'question_id' => 3,
                'name' => "Q3 O3 true",
                'sequence' => 3,
                'isCorrect'=>true,
                'explaination'=>'this is the explaination for correct answer for the question 3'
            ],
            [
                'question_id' => 3,
                'name' => "Q3 O4 false",
                'sequence' => 4,
                'isCorrect'=>false,
                'explaination'=>''
            ],
            // Question 4
            [
                'question_id' => 4,
                'name' => "Q4 O1 false",
                'sequence' => 1,
                'isCorrect'=>false,
                'explaination'=>''
            ],
            [
                'question_id' => 4,
                'name' => "Q4 O2 false",
                'sequence' => 2,
                'isCorrect'=>false,
                'explaination'=>''
            ],
            [
                'question_id' => 4,
                'name' => "Q4 O3 false",
                'sequence' => 3,
                'isCorrect'=>false,
                'explaination'=>''
            ],
            [
                'question_id' => 4,
                'name' => "Q4 O4 true",
                'sequence' => 4,
                'isCorrect'=>true,
                'explaination'=>'this is the explaination for correct answer for the question 4'
            ],
            // Question 5
            [
                'question_id' => 5,
                'name' => "Q5 O1 true",
                'sequence' => 1,
                'isCorrect'=>true,
                'explaination'=>'this is the explaination for correct answer for the question 5'
            ],
            [
                'question_id' => 5,
                'name' => "Q5 O2 false",
                'sequence' => 2,
                'isCorrect'=>false,
                'explaination'=>''
            ],
            [
                'question_id' => 5,
                'name' => "Q5 O3 false",
                'sequence' => 3,
                'isCorrect'=>false,
                'explaination'=>''
            ],
            [
                'question_id' => 5,
                'name' => "Q5 O4 false",
                'sequence' => 4,
                'isCorrect'=>false,
                'explaination'=>''
            ],
            // Question 6
            [
                'question_id' => 6,
                'name' => "Q6 O1 false",
                'sequence' => 1,
                'isCorrect'=>false,
                'explaination'=>''
            ],
            [
                'question_id' => 6,
                'name' => "Q6 O2 true",
                'sequence' => 2,
                'isCorrect'=>true,
                'explaination'=>'this is the explaination for correct answer for the question 6'
            ],
            [
                'question_id' => 6,
                'name' => "Q6 O3 false",
                'sequence' => 3,
                'isCorrect'=>false,
                'explaination'=>''
            ],
            [
                'question_id' => 6,
                'name' => "Q6 O4 false",
                'sequence' => 4,
                'isCorrect'=>false,
                'explaination'=>''
            ],
            // Question 7
            [
                'question_id' => 7,
                'name' => "Q7 O1 false",
                'sequence' => 1,
                'isCorrect'=>false,
                'explaination'=>''
            ],
            [
                'question_id' => 7,
                'name' => "Q7 O2 false",
                'sequence' => 2,
                'isCorrect'=>false,
                'explaination'=>''
            ],
            [
                'question_id' => 7,
                'name' => "Q7 O3 true",
                'sequence' => 3,
                'isCorrect'=>true,
                'explaination'=>'this is the explaination for correct answer for the question 7'
            ],
            [
                'question_id' => 7,
                'name' => "Q7 O4 false",
                'sequence' => 4,
                'isCorrect'=>false,
                'explaination'=>''
            ],
        ];

        DB::table('options')->insert($options);
    }
}
