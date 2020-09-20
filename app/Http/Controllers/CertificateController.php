<?php

namespace App\Http\Controllers;

use App\Certificate;
use App\Exam;
use App\Review;
use Illuminate\Http\Request;
use App\Http\Resources\CertificateResource;

class CertificateController extends Controller
{
    public function index()
    {
        return response()->json(Certificate::with(['exams','orders','reviews'])->get(),200);
        // $certificates= Certificate::with(['exams'])->paginate(3);
        // return CertificateResource::collection($certificates);
    }
    public function paginate(Request $request){
        $per_page=$request->selected_per_page;
        $certificates= Certificate::orderBy('id','desc')->with(['exams','reviews'])->paginate($per_page);
        return CertificateResource::collection($certificates);
    }

    public function store(Request $request)
    {
        $certificate = Certificate::create([
            'name' => $request->name,
            'price' => $request->price,
            'available_for' => $request->available_for,
            'bookedNO' => $request->bookedNO,
            'description' => $request->description,
            'image' => $request->image,
        ]);

        return response()->json([
            'status' => (bool) $certificate,
            'id' =>$certificate->id,
            'data'   => $certificate,
            'message' => $certificate ? 'Certificate Created!' : 'Error Creating Certificate'
        ]);
    }
    public function cReviews(Request $request)
    {
        $certificate= Certificate::where('id',$request->id)->first();
        $reviews=Review::where('certificate_id',$request->id)->orderby('id','desc')->get();
        foreach($reviews as $review){
            $humans=$review->created_at->diffForHumans();
            $review['humans']=$humans;
            $review['cName']=$certificate->name;
        }
        return response()->json($reviews,200);
    }
    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function cExams(Request $request)
    {
        $certificate= Certificate::where('id',$request->id)->first();
        return response()->json($certificate->exams()->with(['questions'])->get(),200);
    }
    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function ncExams(Request $request)
    {
        $id=$request->id;
        $certificate= Certificate::where('id',$request->id)->first();
        $modules = Exam::whereDoesntHave('certificate', function($c) use ($id){
            $c->where('certificate_id', $id);
        })->whereNull('certificate_id')->get();
        return response()->json($modules,200);
    }
    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function unjoinExam(Request $request)
    {
        $exam= Exam::where('id',$request->exam_id)->first();
        $certificate= Certificate::where('id',$request->certificate_id)->first();
        $status = $exam->certificate()->dissociate();
        $exam->save();

        return response()->json([
            'status' => $status,
            'message' => $status ? 'Exam Unjoined!' : 'Error Unjoining Exam'
        ]);
    }

    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function joinExam(Request $request)
    {
        $exam= Exam::where('id',$request->exam_id)->first();
        $certificate= Certificate::where('id',$request->certificate_id)->first();
        $status = $exam->certificate()->associate($certificate->id);
        $exam->save();

        return response()->json([
            'status' => $status,
            'message' => $status ? 'Exam Unjoined!' : 'Error Unjoining Exam'
        ]);
    }

    public function show(Certificate $certificate)
    {
        return response()->json(Certificate::where('id',$certificate->id)->with(['reviews'])->first(),200); 
    }

    public function update(Request $request, Certificate $certificate)
    {
        $status = $certificate->update(
            $request->only(['name', 'description','price','available_for', 'bookedNO', 'image'])
        );

        return response()->json([
            'status' => $status,
            'message' => $status ? 'Certificate Updated!' : 'Error Updating Certificate'
        ]);
    }

    public function updatebookedNO(Request $request)
    {
        $certificate= Certificate::find($request->certificate_id);
        $certificate->bookedNO = $certificate->bookedNO + 1;
        $status = $certificate->save();

        return response()->json([
            'status' => $status,
            'message' => $status ? 'booked Added!' : 'Error Booking Exam'
        ]);
    }
    public function uploadFile(Request $request)
    {
        if($request->hasFile('image')){
            $name = time()."_".$request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('images'), $name);
        }
        return response()->json(asset("images/$name"),201);
    }
    
    public function destroy(Certificate $certificate)
    {
        $status = $certificate->delete();

        return response()->json([
            'status' => $status,
            'message' => $status ? 'Certificate Deleted!' : 'Error Deleting Certificate'
        ]);
    }
}