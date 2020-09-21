<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Session;
use App\Exam;
use App\User;
use Auth;
use App\Http\Resources\SessionResource;
use App\Notifications\MailResultNotification;

class SessionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        // $user=Auth::user();
        return response()->json(Session::with(['answers','user','exam','certificate'])->orderBy('id','desc')->get(),200);
    }
    public function paginate(Request $request)
    {
        //
        // $user=Auth::user();
        $per_page=$request->selected_per_page;
        $sessions= Session::orderBy('id','desc')->with(['answers','user','exam','certificate'])->paginate($per_page);
        return SessionResource::collection($sessions);
    }
    public function cSessions(Request $request)
    {
        //
        $user=Auth::user();
        $certificate_id=$request->certificate_id;
        $sessions=Session::with(['answers','user','exam','certificate'])->where('user_id',$user->id)->where('certificate_id',$certificate_id)->orderBy('id','desc')->get();
        foreach($sessions as $session){
            $session['humans']=$session->created_at->diffForHumans();
        }
        return response()->json($sessions,200);
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
        $session = Session::create([
            'user_id' => $request->user_id,
            'certificate_id' => $request->certificate_id,
            'exam_id' => $request->exam_id,
            'result' => $request->result,
        ]);
        $user=Auth::user();
        $exam=Exam::where('id', $request->exam_id)->first();
        $examName=$exam->name;
        $examType=$exam->type;
        $passing_rate=$exam->passing_rate;
        $examDate=$session->created_at;

        $user->notify(new MailResultNotification($request->result, $examName, $examType, $passing_rate, $examDate));
        return response()->json([
            'status' => (bool) $session,
            'data'   => $session,
            'message' => $session ? 'Session Created!' : 'Error Creating Session'
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
    }
}
