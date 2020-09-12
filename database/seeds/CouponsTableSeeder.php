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
        $coupon->code="ABC123";
        $coupon->type="fixed";
        $coupon->value=30;
        $coupon->save();

        $coupon=new Coupon;
        $coupon->code="DEF456";
        $coupon->type="percent";
        $coupon->percent_off=50;
        $coupon->save();
    }
}
