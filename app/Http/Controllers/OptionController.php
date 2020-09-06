<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Option;
class OptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return response()->json(Option::orderBy('sequence','asc')->get(),200);
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
        $option = Option::create([
            'question_id' => $request->question_id,
            'name' => $request->name,
            'sequence' => $request->sequence,
            'isCorrect' => $request->isCorrect,
            'explaination' => $request->explaination,
            'image'=>$request->image,
        ]);

        return response()->json([
            'status' => (bool) $option,
            'data'   => $option,
            'message' => $option ? 'Option Created!' : 'Error Creating Option'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Option $option)
    {
        $status = $option->update(
            $request->only(['question_id','name', 'sequence', 'isCorrect','explaination','image'])
        );

        return response()->json([
            'status' => $status,
            'message' => $status ? 'Option Updated!' : 'Error Updating Option'
        ]);
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function updateSequences(Request $request)
    {
        $option=Option::where('id',$request->id);
        $status = $option->update(
            $request->only(['sequence'])
        );

        return response()->json([
            'status' => $status,
            'message' => $status ? 'Sequence Updated!' : 'Error Updating Sequence'
        ]);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Option $option)
    {
        $status = $option->delete();

        return response()->json([
            'status' => $status,
            'message' => $status ? 'Option Deleted!' : 'Error Deleting Option'
        ]);
    }
}
