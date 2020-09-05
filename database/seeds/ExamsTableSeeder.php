<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
class ExamsTableSeeder extends Seeder
{
    /**
    * Run the database seeds.
    *
    * @return void
    */
    public function run()
    {
        //
        $exams = [
            [
                'certificate_id'=>1,
                'name' => "Planning",
                'type' => "Exam",
                'passing_rate' =>50.00,
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                tempor incididunt ut labore et dolore magna aliqua consequat.',
                'duration' => 1,
                'created_at' => new DateTime,
                'updated_at' => null,
            ],
            [
                'certificate_id'=>2,
                'name' => "Machine Learning",
                'type' => "Practical Test",
                'passing_rate' => null,
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                tempor incididunt ut labore et dolore magna aliqua consequat.',
                'duration' => 1,
                'created_at' => new DateTime,
                'updated_at' => null,
            ],
            [
                'certificate_id'=>1,
                'name' => "Monitoring",
                'type' => "Exam",
                'passing_rate' => 60.00,
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                tempor incididunt ut labore et dolore magna aliqua consequat.',
                'duration' => 1,
                'created_at' => new DateTime,
                'updated_at' => null,
            ],
            [
                'certificate_id'=>1,
                'name' => 'Deep learning',
                'type' => "Practical Test",
                'passing_rate' =>null,
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                tempor incididunt ut labore et dolore magna aliqua consequat.',
                'duration' => 2,
                'created_at' => new DateTime,
                'updated_at' => null,
            ]
        ];
        $exam_questions = [
            [
                'exam_id' => 1,
                'question_id' => 1,
                'sequence' => 1
            ],
            [
                'exam_id' => 1,
                'question_id' => 2,
                'sequence' => 2
            ],
            [
                'exam_id' => 1,
                'question_id' => 3,
                'sequence' => 3
            ],
            [
                'exam_id' => 1,
                'question_id' => 4,
                'sequence' => 4
            ],
            [
                'exam_id' => 1,
                'question_id' => 5,
                'sequence' => 5
            ],
            [
                'exam_id' => 1,
                'question_id' => 6,
                'sequence' => 6
            ],
            [
                'exam_id' => 1,
                'question_id' => 7,
                'sequence' => 7
            ],
            [
                'exam_id' => 2,
                'question_id' => 1,
                'sequence' => 1
            ],
            [
                'exam_id' => 2,
                'question_id' => 2,
                'sequence' => 2
            ],
            [
                'exam_id' => 3,
                'question_id' => 1,
                'sequence' => 1
            ],
            [
                'exam_id' => 3,
                'question_id' => 2,
                'sequence' => 2
            ],
            [
                'exam_id' => 4,
                'question_id' => 1,
                'sequence' => 1
            ],
            [
                'exam_id' => 4,
                'question_id' => 2,
                'sequence' => 2
            ],
        ];

        DB::table('exams')->insert($exams);
        DB::table('exam_question')->insert($exam_questions);
    }
}
