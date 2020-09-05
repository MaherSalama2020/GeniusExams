<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Question;
use App\Certificate;
use App\Order;

class Exam extends Model
{
    use SoftDeletes;

    protected $fillable = [
        //type must be exam or practical test
        //change units to done
        'name', 'type', 'passing_rate', 'description',  'certificate_id', 'duration'
    ];

    // public function orders(){
    //     return $this->hasMany(Order::class);
    // }

    public function certificate(){
        return $this->belongsto(Certificate::class);
    }
    public function questions()
    {
        return $this->belongsToMany(Question::class, 'exam_question')->withPivot('sequence')->orderby('exam_question.sequence');
    }
}