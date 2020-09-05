<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Exam;
use App\Order;
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

    public function exams(){
        return $this->hasMany(Exam::class);
    }
}
