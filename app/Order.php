<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\User;
use App\Certificate;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'certificate_id', 'user_id','date', 'time','is_finished'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // public function exam()
    // {
    //     return $this->belongsTo(Exam::class, 'exam_id');
    // }
    public function certificate()
    {
        return $this->belongsTo(Certificate::class, 'certificate_id');
    }
}