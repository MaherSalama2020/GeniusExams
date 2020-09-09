<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use App\VerifyUser;
use App\Mail\VerifyMail;
use Validator;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use App\Notifications\MailVerifyNotification;


class UserController extends Controller
{
    public function index()
    {
        return response()->json(User::with(['orders'])->get());
    }
    public function paginate(Request $request){
        $per_page=$request->selected_per_page;
        $users= User::orderBy('id','desc')->with(['orders'])->paginate($per_page);
        return UserResource::collection($users);
    }
    public function checkEmail(Request $request)
    {
        $val=$request->only('email');
        $email=User::where('email',$val)->first();
        if($email) return response()->json("yes");
        else 
        return response()->json("no");
    }

    public function login(Request $request)
    {
        $status = 401;
        $response = ['error' => 'Unauthorised'];
  

        if (Auth::attempt($request->only(['email', 'password']))) {
            $user=Auth::user();
            if(!$user->verified){
                return response()->json([
                    "status"=>422,
                    "message"=> "You didn't verify your E-mail."]);
            }
            $status = 200;
            $response = [
                'status'=> true,
                'user' => Auth::user(),
                'token' => Auth::user()->createToken('geniusexams')->accessToken,
            ];
        }

        return response()->json($response, $status);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:50',
            'email' => 'required|email',
            'password' => 'required|min:5',
            'c_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $data = $request->only(['name', 'email', 'password']);
        $data['password'] = bcrypt($data['password']);

        $user = User::create($data);
        $user->is_admin = 0;
        $user->verified=0;
        
        $vtoken=sha1(time());
        $verifyUser = VerifyUser::create([
            'user_id' => $user->id,
            'token' => $vtoken
        ]);
        // \Mail::to($user->email)->send(new VerifyMail($user));
        $user->notify(new \App\Notifications\MailVerifyNotification($vtoken));

        return response()->json([
            'message'=>  'You need to verify your registartion via ' . $user->email,
            'user' => $user,
            'token' => $user->createToken('genius')->accessToken,
        ]);
    }

    public function show(User $user)
    {
        return response()->json($user);
    }

    public function showOrders(User $user)
    {
        return response()->json($user->orders()->with(['certificate'])->orderBy('id','desc')->get());
    }

    // public function forgot_password(Request $request)
    // {
    //     $input = $request->all();
    //     $rules = array(
    //         'email' => "required|email",
    //     );
    //     $arr=[];
    //     $validator = Validator::make($input, $rules);
    //     if ($validator->fails()) {
    //         $arr = array("status" => 400, "message" => $validator->errors()->first(), "data" => array());
    //     } else {
    //         try {
    //             $response = Password::sendResetLink($request->only('email'), function (Message $message) {
    //                 $message->subject($this->getEmailSubject());
    //             });
    //             switch ($response) {
    //                 case Password::RESET_LINK_SENT:
    //                     return $arr=array("status" => 200, "message" => trans($response), "data" => array());
    //                 case Password::INVALID_USER:
    //                     return $arr=array("status" => 400, "message" => trans($response), "data" => array());
    //             }
    //         } catch (\Swift_TransportException $ex) {
    //             $arr = array("status" => 400, "message" => $ex->getMessage(), "data" => []);
    //         } catch (Exception $ex) {
    //             $arr = array("status" => 400, "message" => $ex->getMessage(), "data" => []);
    //         }
    //     }
    //     return \Response::json($arr);
    // }

    public function change_password(Request $request)
    {
        $input = $request->all();
        $userid = Auth::guard('api')->user()->id;
        $rules = array(
            'old_password' => 'required',
            'new_password' => 'required|min:5',
            'confirm_password' => 'required|same:new_password',
        );
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $arr = array("status" => 400, "message" => $validator->errors()->first(), "data" => array());
        } else {
            try {
                if ((Hash::check(request('old_password'), Auth::user()->password)) == false) {
                    $arr = array("status" => false, "message" => "Check your old password.", "data" => array());
                } else if ((Hash::check(request('new_password'), Auth::user()->password)) == true) {
                    $arr = array("status" => false, "message" => "Please enter a password which is not similar then current password.", "data" => array());
                } else {
                    User::where('id', $userid)->update(['password' => Hash::make($input['new_password'])]);
                    $arr = array("status" => true, "message" => "Password updated successfully.", "data" => array());
                }
            } catch (\Exception $ex) {
                if (isset($ex->errorInfo[2])) {
                    $msg = $ex->errorInfo[2];
                } else {
                    $msg = $ex->getMessage();
                }
                $arr = array("status" => 400, "message" => $msg, "data" => array());
            }
        }
        return \Response::json($arr);
    }

    //Verify User
    public function verifyUser(Request $request)
    {
        $token=$request->verification_token;
        $verifyUser = VerifyUser::where('token', $token)->first();
        $message="";
        if(isset($verifyUser) ){
            $user = $verifyUser->user;
            if(!$user->verified) {
            $verifyUser->user->verified = 1;
            $verifyUser->user->save();
            $message = "Your e-mail is verified. You can now login.";
            } else {
            $message = "Your e-mail is already verified. You can now login.";
            }
        } else {
            return $message= "Sorry your email cannot be identified.";
        }
        return response()->json([
            'message' => $message,
        ]);
    }

}