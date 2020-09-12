<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Coupon;
class CouponController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $coupon=Coupon::where('code', $request->code)->first();
        if(!$coupon){
            return response()->json([
                'status'=>false,
                'message' => "Invalid coupon code. please try again."
            ]);
        }
        session()->put('coupon', [
            'name'=>$coupon->code,
            'discount'=>$coupon->discount(Cart::subtotal()) ,
        ]);
        return response()->json([
            'status'=>true,
            'message' => "Coupon has been applied"
        ]);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        session()->forget('coupon');
        return response()->json([
            'status'=>true,
            'message' => "Coupon has been removed"
        ]);
    }
}
