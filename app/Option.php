<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Question;

class Option extends Model
{
    //
    use SoftDeletes;
    
    protected $fillable = [
        'question_id', 'name', 'sequence',  'isCorrect', 'explaination'
    ];
    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }
}
