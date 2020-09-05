<?php
namespace App\Http\Controllers;
use \App\Notifications\MailContactUsNotification;
use Illuminate\Http\Request;
use Mail;
use Auth;
use App\User;

class ContactUsController extends Controller
{
    public function handleForm(Request $request)
    {
        $name="Guest";
        $user=Auth::user();
        if($user)
            $name=$user->name;
        $this->validate($request, ['subject' => 'required', 'email' => 'required|email', 'message' => 'required|min:10']);

        // $data = ['name' => $name ,'subject' =>$request->subject, 'email' => $request->email , 'messageBody' => $request->message ];
        $email = $request->email;
        $subject = $request->subject;
        $message= $request->message;
        $admin=User::where('id',1)->first();
        $admin->notify(new MailContactUsNotification($name, $email, $subject, $message));
        
        return response()->json([
            'message' =>   'We receive your E-mail!' ,
        ]);

    }

}