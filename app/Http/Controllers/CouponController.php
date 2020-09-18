<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Coupon;
use App\User;
use DB;
use App\Http\Resources\CouponResource;
use App\Notifications\MailCouponNotification;

class CouponController extends Controller
{
    public function index()
    {
        return response()->json(Coupon::with(['user'])->get(),200);
    }
    public function paginate(Request $request){
        $per_page=$request->selected_per_page;
        $coupons= Coupon::orderBy('id','desc')->paginate($per_page);
        return CouponResource::collection($coupons);
    }
    public function store(Request $request)
    {
        
        $coupon = Coupon::create([
            'email' => $request->email,
            'code' => $request->code,
            'type' => $request->type,
            'value' => $request->value,
            'percent_off' => $request->percent_off,
            'is_used' => $request->is_used,
        ]);
        
        $coupon->notify(new MailCouponNotification($request->code, $request->type, $request->value, $request->percent_off));
        return response()->json([
            'status' => (bool) $coupon,
            'id' =>$coupon->id,
            'data'   => $coupon,
            'user' =>$coupon->user,
            'message' => $coupon ? 'Coupon Created!' : 'Error Creating Coupon'
        ]);
    }
    public function update(Request $request, Coupon $coupon)
    {
        $status = $coupon->update(
            $request->only(['email', 'code','type','value', 'percent_off', 'is_used'])
        );

        return response()->json([
            'status' => $status,
            'message' => $status ? 'Coupon Updated!' : 'Error Updating Coupon'
        ]);
    }
    public function checkCode(Request $request)
    {
        $val=$request->only('code');
        $code=Coupon::where('code',$val)->first();
        if($code) return response()->json("yes");
        else 
        return response()->json("no");
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function ApplyCoupon(Request $request)
    {
        //
        $coupon=Coupon::where(DB::raw('BINARY `code`'), $request->couponCode)->first();
        
        $total=$request->total;
        if(!$coupon){
            return response()->json([
                'status'=>false,
                'subtotal'=> $total,
                'message' => "Invalid coupon code. please try again."
            ]);
        }
        if($coupon->is_used){
            return response()->json([
                'status'=>false,
                'subtotal'=> $total,
                'message' => "Expired coupon. please try another."
            ]);
        }
        // session()->put('coupon', [
        //     'name'=>$coupon->code,
        //     'discount'=>$coupon->discount(Cart::subtotal()) ,
        // ]);
        $subtotal=0;
        if($coupon->type=='fixed')
        $subtotal=$total-$coupon->value;
        else if($coupon->type=='percent_off')
        $subtotal=$total-(($total*$coupon->percent_off)/100);
        $coupon->is_used=true;
        $coupon->save();
        return response()->json([
            'status'=>true,
            'subtotal'=> $subtotal,
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
