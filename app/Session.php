<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Answer;
use App\User;
use App\Exam;
use App\Certificate;
class Session extends Model
{
    use SoftDeletes;

    //
    protected $fillable = [
        'user_id','certificate_id','exam_id', 'result'
    ];

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function certificate()
    {
        return $this->belongsTo(Certificate::class, 'certificate_id');
    }
    public function exam()
    {
        return $this->belongsTo(Exam::class, 'exam_id');
    }
}
