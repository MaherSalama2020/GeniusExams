<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Exam;
use App\Order;
use App\Review;
class Certificate extends Model
{
    //
    use SoftDeletes;
    // protected $guarded = [];
    protected $fillable = [
        'name', 'price', 'bookedNO', 'available_for' ,'description', 'image'
    ];
    public function orders(){
        return $this->hasMany(Order::class);
    }
    // public function rates(){
    //     return $this->hasMany(Rate::class);
    // }
    public function reviews(){
        return $this->hasMany(Review::class);
    }
    public function exams(){
        return $this->hasMany(Exam::class);
    }
    public function presentPrice(){
        return money_format('$%i', $this->price/100);
    }
    public function scopeMightAlsoLike($query)
    {
        return $query->inRandomOrder()->take(4);
    }
}
