<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SliderController extends Controller
{
    //
    public function uploadFile(Request $request)
    {
        if($request->hasFile('image')){
            $name = $request->name;
            $request->file('image')->move(public_path('images/slider'), $name);
        }
        return response()->json(asset("images/slider/$name"),201);
    }
}
