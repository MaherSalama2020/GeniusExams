<?php

namespace App\Http\Controllers;

use App\Rate;
use App\Certificate;
use Illuminate\Http\Request;
use Auth;
class RateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }
    public function cRate(Request $request)
    {
        //
        $avgValue= Rate::where('certificate_id',$request->certificate_id)->avg('value');
        $avgValue=number_format($avgValue, 1);

        return response()->json([
            'status' => (bool) $avgValue,
            'rate'   => (double)$avgValue,
            'message' => $avgValue ? 'Rate get!' : 'Error getting Rate'
        ]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $prevRate=Rate::where('certificate_id',$request->certificate_id)->where('user_id',Auth::user()->id)->first();
        // update rate for the associated certificate
        $certificate=Certificate::where('id',$request->certificate_id)->first();
  
        // 
        if(!$prevRate){
            $rate = Rate::create([
                'certificate_id' => $request->certificate_id,
                'user_id' => Auth::user()->id,
                'value' => $request->value,
            ]);
            $avgValue= Rate::where('certificate_id',$request->certificate_id)->avg('value');
            $avgValue=number_format($avgValue, 1);
            $certificate->rate=$avgValue;
            $certificate->ratedNO+=1;
            $certificate->save();
            return response()->json([
                'status' => (bool) $rate,
                'data'   => $rate,
                'message' => $rate ? 'Rate Created!' : 'Error Creating Rate'
            ]);
        } else{
           $prevRate->certificate_id=$request->certificate_id;
           $prevRate->value=$request->value;
           $prevRate->save();
           $avgValue= Rate::where('certificate_id',$request->certificate_id)->avg('value');
           $avgValue=number_format($avgValue, 1);
           $certificate->rate=$avgValue;
           $certificate->save();
            return response()->json([
                'status' => (bool) $prevRate,
                'data' => $prevRate,
                'message' => $prevRate ? 'Rate Updated!' : 'Error Updating Rate'
            ]);
        }


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\r  $r
     * @return \Illuminate\Http\Response
     */
    public function show(Rate $rate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\r  $r
     * @return \Illuminate\Http\Response
     */
    public function edit(Rate $rate)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\r  $r
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Rate $rate)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\r  $r
     * @return \Illuminate\Http\Response
     */
    public function destroy(Rate $rate)
    {
        //
    }
}
