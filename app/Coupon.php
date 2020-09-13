<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\User;
class Coupon extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code', 'user_id','type', 'value','percent_off','is_used'
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
