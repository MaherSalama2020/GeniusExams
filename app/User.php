<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use App\Order;
use App\Coupon;
use App\Rate;
use App\Review;
class User extends Authenticatable
{
    use Notifiable, SoftDeletes, HasApiTokens;

    protected $fillable = [
        'name', 'email', 'country', 'password', 'verified'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    // public function coupons()
    // {
    //     return $this->hasMany(Coupon::class);
    // }
    /**
    * Override the mail body for reset password notification mail.
    */
    public function sendPasswordResetNotification($token)
    {
        $email=request()->email;
        $this->notify(new \App\Notifications\MailResetPasswordNotification($token, $email));
    }
    public function verifyUser()
    {
        return $this->hasOne('App\VerifyUser');
    }
    public function rates(){
        return $this->hasMany(Rate::class);
    }
    public function reviews(){
        return $this->hasMany(Review::class);
    }
}