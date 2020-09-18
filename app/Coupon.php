<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use App\User;
class Coupon extends Model
{
    use SoftDeletes;
    use Notifiable;
    protected $fillable = [
        'code', 'email','type', 'value','percent_off','is_used'
    ];
    // public function user()
    // {
    //     return $this->belongsTo(User::class, 'user_email');
    // }
}
