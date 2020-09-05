<?php

namespace App\Http\Controllers;

use App\Exam;
use App\Question;
use Illuminate\Http\Request;
use App\Http\Resources\ExamResource;
use App\Option;

class ExamController extends Controller
{
    public function index()
    {
        return response()->json(Exam::with(['certificate'])->get(),200);
    }
    public function paginate(Request $request){
        $per_page=$request->selected_per_page;
        $questions= Exam::orderBy('id','desc')->with(['certificate','questions'])->paginate($per_page);
        return ExamResource::collection($questions);
    }

    public function store(Request $request)
    {
        $exam = Exam::create([
            'name' => $request->name,
            'certificate_id' => $request->certificate_id,
            'description' => $request->description,
            'type' => $request->type,
            'passing_rate' => $request->passing_rate,
            'duration' => $request->duration,
        ]);

        return response()->json([
            'status' => (bool) $exam,
            'id' => $exam->id,
            'data'   => $exam,
            'message' => $exam ? 'Exam Created!' : 'Error Creating Exam'
        ]);
    }
    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function eQuestions(Request $request)
    {
        // return response()->json(Question::where('id',$request->id)->get(),200); 
        $exam= Exam::where('id',$request->id)->first();
        $questions= $exam->questions()->orderBy('id','asc')->get();
        foreach ($questions as $question){ 
            $options=Option::where('question_id', $question->id)->orderBy('sequence','asc')->get();
            $question->options=$options;
            }
        return response()->json($questions,200);
    }
    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function neQuestions(Request $request)
    {
        $id=$request->id;
        $exam= Exam::where('id',$request->id)->first();
        $modules = Question::whereDoesntHave('exams', function($p) use ($id){
            $p->where('exam_id', $id);
        })->get();
        return response()->json($modules,200);
    }
    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function unjoinQuestion(Request $request)
    {
        $question= Question::where('id',$request->question_id)->first();
        $exam= Exam::where('id',$request->exam_id)->first();
        $status = $question->exams()->detach($exam);
        return response()->json([
            'status' => $status,
            'message' => $status ? 'Question Unjoined!' : 'Error Unjoining Question'
        ]);
    }

    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function joinQuestion(Request $request)
    {
        $question= Question::where('id',$request->question_id)->first();
        $exam= Exam::where('id',$request->exam_id)->first();
        $status = $question->exams()->attach($exam, ['sequence'=> $request->sequence ]);
        return response()->json([
            'status' => $status,
            'message' => $status ? 'Question joined!' : 'Error joining Question'
        ]);
    }

    public function show(Exam $exam)
    {
        return response()->json($exam,200); 
    }

    public function uploadFile(Request $request)
    {
        if($request->hasFile('image')){
            $name = time()."_".$request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('images'), $name);
        }
        return response()->json(asset("images/$name"),201);
    }

    public function update(Request $request, Exam $exam)
    {
        $status = $exam->update(
            $request->only(['certificate_id','name', 'description', 'type','passing_rate','duration'])
        );

        return response()->json([
            'status' => $status,
            'message' => $status ? 'Exam Updated!' : 'Error Updating Exam'
        ]);
    }

 
    public function destroy(Exam $exam)
    {
        $status = $exam->delete();

        return response()->json([
            'status' => $status,
            'message' => $status ? 'Exam Deleted!' : 'Error Deleting Exam'
        ]);
    }
}