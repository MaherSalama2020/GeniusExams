<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Option;
use App\Exam;

class Question extends Model
{
    //
    use SoftDeletes;
    protected $fillable = [
        'name', 'image'
    ];

    public function options(){
        return $this->hasMany(Option::class);
    }
    public function exams()
    {
        return $this->belongsToMany(Exam::class, 'exam_question')->withPivot('sequence');
    }
}
