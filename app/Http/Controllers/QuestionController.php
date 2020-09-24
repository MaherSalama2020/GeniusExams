<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Question;
use App\Exam;
use App\Http\Resources\QuestionResource;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return response()->json(Question::with(['options'=> function ($q) {
            $q->orderBy('sequence', 'asc');
            }])->get(),200);
    }
    public function paginate(Request $request){
        $per_page=$request->selected_per_page;
        $questions= Question::orderBy('id','desc')->with(['options'=> function ($q) {
            $q->orderBy('sequence', 'asc');
            },'exams'])->paginate($per_page);
        return QuestionResource::collection($questions);
    }
    public function uploadFile(Request $request)
    {
        if($request->hasFile('image')){
            $name = time()."_".$request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('images/questions'), $name);
        }
        return response()->json(asset("images/questions/$name"),201);
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
        $question = Question::create([
            'name' => $request->name,
            'image' => $request->image,
        ]);

        return response()->json([
            'status' => (bool) $question,
            'data' =>$question,
            'message' => $question ? 'Question Created!' : 'Error Creating Question'
        ]);
    }

    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function qOptions(Request $request)
    {
        // return response()->json(Question::where('id',$request->id)->get(),200); 
        return response()->json(Question::with(['options'=> function ($q) {
            $q->orderBy('sequence', 'asc');
            }])->find($request->id),200);
    }

    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function qExams(Request $request)
    {
        // return response()->json(Question::where('id',$request->id)->get(),200); 
        $question= Question::where('id',$request->id)->first();
        return response()->json($question->exams()->get(),200);
    }
    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function nqExams(Request $request)
    {
        // return response()->json(Question::where('id',$request->id)->get(),200); 
        $id=$request->id;
        $question= Question::where('id',$request->id)->first();
        $modules = Exam::whereDoesntHave('questions', function($q) use ($id){
            $q->where('question_id', $id);
        })->get();
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
        $question= Question::where('id',$request->question_id)->first();
        $exam= Exam::where('id',$request->exam_id)->first();
        $status = $exam->questions()->detach($question);
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
        $question= Question::where('id',$request->question_id)->first();
        $exam= Exam::where('id',$request->exam_id)->first();
        $status = $exam->questions()->attach($question, ['sequence'=> $request->sequence ]);
        return response()->json([
            'status' => $status,
            'message' => $status ? 'Exam joined!' : 'Error joining Exam'
        ]);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Question $question)
    {
        return response()->json($question,200); 
    }

    public function update(Request $request, Question $question)
    {
        $status = $question->update(
            $request->only(['name','image'])
        );

        return response()->json([
            'status' => $status,
            'message' => $status ? 'Question Updated!' : 'Error Updating Question'
        ]);
    }

    public function updatePivot(Request $request)
    {
        $question=Question::where('id',$request->question_id)->first();
        $question->name=$request->name;
        $question->image=$request->image;
        $question->save();
  
        $exam_id=$request->exam_id;
        $status=$question->exams()->sync([$exam_id => [ 'sequence' => $request->sequence] ], false);
    //    $status= $question->exams()->updateExistingPivot($exam_id, ['sequence' => $request->sequence]);
        return response()->json([
            'status' => $status,
            'message' => $status ? 'Question Updated!' : 'Error Updating Question'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Question $question)
    {
        $status = $question->delete();

        return response()->json([
            'status' => $status,
            'message' => $status ? 'Question Deleted!' : 'Error Deleting Question'
        ]);
    }
}
