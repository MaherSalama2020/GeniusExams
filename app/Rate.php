<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Certificate;
use App\User;
class Rate extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'certificate_id', 'user_id','value'
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
