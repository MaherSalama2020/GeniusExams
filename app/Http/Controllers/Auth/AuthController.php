<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Hash;
use Illuminate\Auth\Events\PasswordReset;
use App\User;
use DB;
use Illuminate\Contracts\Hashing\Hasher as HasherContract;
class AuthController extends Controller
{
    public function __construct(HasherContract $hasher)
    {
        $this->hasher = $hasher;
    }

    use SendsPasswordResetEmails;
    //
    /**
    * Send password reset link. 
    */
    public function sendPasswordResetLink(Request $request)
    {
        return $this->sendResetLinkEmail($request);
    }
    /**
    * Get the response for a successful password reset link.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  string  $response
    * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
    */
    protected function sendResetLinkResponse(Request $request, $response)
    {
        return response()->json([
            'status' => true,
            'message' => 'Password reset email sent.',
            'data' => $response
        ]);
    }

    /**
    * Get the response for a failed password reset link.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  string  $response
    * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
    */
    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        return response()->json(['status' => false,'message' => 'Email could not be sent to this email address.']);
    }
    /**
    * Handle reset password 
    */
    public function callResetPassword(Request $request)
    {
        $token=$request->token;
        $reset = DB::table('password_resets')->where('email', $request->email)->first();
        if( $this->hasher->check($request->token, $reset->token)){
            $user=User::where('email',$request->email)->first();
            $password=$request->password;
            $user->password = bcrypt($password);
            $user->save();
            event(new PasswordReset($user));
            return response()->json(['status'=>true, 'message' => 'Password reset successfully.']);
        }else{
            return response()->json(['status'=>false, 'message' => 'Failed, Invalid Token.']);
        }
        
    }

  
}
