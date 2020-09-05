<?php

namespace App\Http\Controllers;

use App\Order;
use Auth;
use Illuminate\Http\Request;
use App\Http\Resources\OrderResource;

class OrderController extends Controller
{
    public function index()
    {
        return response()->json(Order::with(['certificate', 'user'])->orderBy('id','desc')->get(),200);
    }
    public function paginate(Request $request){
        $per_page=$request->selected_per_page;
        $orders= Order::orderBy('id','desc')->with(['certificate', 'user'])->paginate($per_page);
        return OrderResource::collection($orders);
    }
    public function finishOrder(Order $order)
    {
        $order->is_finished = true;
        $status = $order->save();

        return response()->json([
            'status'    => $status,
            'data'      => $order,
            'message'   => $status ? 'Order Finished!' : 'Error Delivering Order'
        ]);
    }

    public function canOrder(Request $request){
        $user_id=Auth::id();
        $orders=Order::where('user_id',$user_id)->where('certificate_id',$request->certificate_id)->get();
        if(count($orders)==0) {
            return response()->json(['canOrder'=>'yes']);
        }
        $is_finished=true;
        foreach($orders as $order){
            $is_finished=$is_finished && $order->is_finished;
        }
        if(!$is_finished){
            return response()->json(['canOrder'=>'no']);
        } else{
            return response()->json(['canOrder'=>'yes']);
        }
    }
    public function store(Request $request)
    {

        $order = Order::create([
            'certificate_id' => $request->certificate_id,
            'date' => $request->date,
            'time' => $request->time,
            'user_id' => Auth::id(),
        ]);

        return response()->json([
            'status' => (bool) $order,
            'data'   => $order,
            'message' => $order ? 'Order Created!' : 'Error Creating Order'
        ]);
    }
    public function adminStore(Request $request)
    {
        $order = Order::create([
            'certificate_id' => $request->certificate_id,
            'date' => $request->date,
            'time' => $request->time,
            'user_id' => $request->user_id,
        ]);

        return response()->json([
            'status' => (bool) $order,
            'data'   => $order,
            'message' => $order ? 'Order Created!' : 'Error Creating Order'
        ]);
    }
    public function show(Order $order)
    {
        return response()->json($order,200);
    }

    public function update(Request $request, Order $order)
    {
        $status = $order->update(
            $request->only(['certificate_id', 'user_id','date','time','is_finished'])
        );

        return response()->json([
            'status' => $status,
            'message' => $status ? 'Order Updated!' : 'Error Updating Order'
        ]);
    }

    public function destroy(Order $order)
    {
        $status = $order->delete();

        return response()->json([
            'status' => $status,
            'message' => $status ? 'Order Deleted!' : 'Error Deleting Order'
        ]);
    }
}