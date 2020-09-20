<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Review;
use App\Certificate;
use Auth;
use App\Http\Resources\ReviewResource;
class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return response()->json(Review::with(['user', 'certificate'])->where('happy',1)-> orderBy('created_at', 'desc')->take(25)->get(),200);
    }
    public function paginate(Request $request)
    {
        //
        $per_page=$request->selected_per_page;
        $reviews= Review::with(['user','certificate'])->orderBy('id','desc')->paginate($per_page);
        foreach($reviews as $review){
            $humans=$review->created_at->diffForHumans();
            $review['humans']=$humans;
            // return $review->created_at->diffForHumans();
        }
        return ReviewResource::collection($reviews);
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
        $certificate=Certificate::where('id',$request->certificate_id)->first();
        $review = Review::create([
            'user_id' => Auth::user()->id,
            'certificate_id' => $request->certificate_id,
            'value' => $request->value,
            'review' => $request->review,
        ]);
        if((bool) $review){
            $avgValue= Review::where('certificate_id',$request->certificate_id)->avg('value');
            $avgValue=number_format($avgValue, 1);
            $certificate->rate=$avgValue;
            $certificate->ratedNO+=1;
            $certificate->save();
        }
        return response()->json([
            'status' => (bool) $review,
            'data'   => $review,
            'message' => $review ? 'Thank you!! Review Saved!' : 'Something went wrong!!'
        ]);
    }
    public function cRate(Request $request)
    {
        //
        $avgValue= Review::where('certificate_id',$request->certificate_id)->avg('value');
        $avgValue=number_format($avgValue, 1);

        return response()->json([
            'status' => (bool) $avgValue,
            'rate'   => (double)$avgValue,
            'message' => $avgValue ? 'Rate get!' : 'Error getting Rate'
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
    public function update(Request $request, $id)
    {
        //
        $review=Review::where('id',$id)->first();
        $review->happy=!$review->happy;
        $status = $review->save();
        return response()->json([
            'status' => $status,
            'message' => $status ? 'Review U[dated!' : 'Error Updating Review'
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
        $review=Review::where('id',$id)->first();
        $status = $review->delete();

        return response()->json([
            'status' => $status,
            'message' => $status ? 'Review Deleted!' : 'Error Deleting Review'
        ]);
    }
}
