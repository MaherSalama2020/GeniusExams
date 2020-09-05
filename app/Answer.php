<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Session;
use App\Question;
use App\Option;

class Answer extends Model
{
    use SoftDeletes;
    //
    protected $fillable = [
        'session_id', 
        'question_id',
        'option_id'
    ];

    public function session()
    {
        return $this->belongsTo(Session::class, 'session_id');
    }

    public function question()
    {
        return $this->belongsTo(Question::class, 'questione_id');
    }
    public function option()
    {
        return $this->belongsTo(Option::class, 'option_id');
    }
}
