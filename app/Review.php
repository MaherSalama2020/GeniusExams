<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\User;
use App\Certificate;
class Review extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'user_id','certificate_id', 'review', 'value'
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function certificate()
    {
        return $this->belongsTo(Certificate::class, 'certificate_id');
    }
}
