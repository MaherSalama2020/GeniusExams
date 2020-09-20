<?php

use Illuminate\Database\Seeder;
use App\Review;
class ReviewsTableSeeder extends Seeder
{
    /**
    * Run the database seeds.
    *
    * @return void
    */
    public function run()
    {
        //
        $review=new Review;
        $review->user_id=1;
        $review->certificate_id=1;
        $review->value=1;
        $review->review="User 1 Review User 1 ReviewUser 1 ReviewUser 1 ReviewUser 1 ReviewUser 1 ReviewUser 1 ReviewUser 1 ReviewUser 1 ReviewUser 1 ReviewUser 1 ReviewUser 1 ReviewUser 1 ReviewUser 1 ReviewUser 1 ReviewUser 1 ReviewUser 1 ReviewUser 1 ReviewUser 1 ReviewUser 1 ReviewUser 1 Review";
        $review->save();

        $review=new Review;
        $review->user_id=2;
        $review->certificate_id=2;
        $review->value=2;
        $review->review="User 2 Review User 2 Review User 2 Review User 2 Review User 2 Review User 2 Review User 2 Review User 2 Review User 2 Review User 2 Review User 2 Review User 2 Review User 2 Review User 2 Review User 2 Review User 2 Review User 2 Review User 2 Review User 2 Review User 2 Review";
        $review->save();

        $review=new Review;
        $review->user_id=3;
        $review->certificate_id=2;
        $review->value=2;
        $review->review="User 3 Review User 3 Review User 3 Review User 3 Review User 3 Review User 3 Review User 3 Review User 3 Review User 3 Review User 3 Review User 3 Review User 3 Review User 3 Review User 3 Review User 3 Review User 3 Review User 3 Review User 3 Review User 3 Review User 3 Review";
        $review->save();
    }
}
