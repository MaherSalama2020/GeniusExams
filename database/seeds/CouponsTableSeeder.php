<?php

use Illuminate\Database\Seeder;
use App\Coupon;
class CouponsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $coupon=new Coupon;
        $coupon->email="vv@vv.vv";
        $coupon->code="ABC123";
        $coupon->type="fixed";
        $coupon->value=30;
        $coupon->is_used=0;
        $coupon->save();

        $coupon=new Coupon;
        $coupon->email="aa@aa.aa";
        $coupon->code="DEF456";
        $coupon->type="percent_off";
        $coupon->percent_off=50;
        $coupon->is_used=0;
        $coupon->save();
    }
}
