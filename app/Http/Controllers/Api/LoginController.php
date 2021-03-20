<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Rules\PasswordRule;
use App\Services\ResponseMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    /**
     * Attempts to login user
     *    - throws a Bad request code if user input is not well formatted
     *    - Throws 401 if credentials are incorrect or if email has not been verified or if accou t is inactive
     *    -  return 200 on success
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = validator($request->all(),[
            'email' => ['required','email'],
            'password' => ['required',new PasswordRule(),'min:6']
        ]);

        if($validator->fails()){
            $response = ResponseMessage::errorResponse("Incorrect format", $validator->errors());
            return response()->json($response, 400);
        }

        $credential = ['email'=>$request->email,'password'=>$request->password];
        if( !auth()->validate($credential) ){
            // check if account is verified
            $response = ResponseMessage::errorResponse("incorrect Username/password");
            return response()->json($response, 401);
        }

        $user = User::where(['email'=>$request->email])->first();

        if(is_null($user->email_verified_at) ){
            $response = ResponseMessage::errorResponse("Email not verified");
            return response()->json($response, 401);
        }

        if($user->isActive != true){
            $response = ResponseMessage::errorResponse("Account not active");
            return response()->json($response, 401);
        }

        if(auth()->attempt($credential) ){
            $abilities = ['user:post,edit'];
            if($user->roles =='admin'){
                $abilities = ['user:all'];
            }

            $token = $request->user()->createToken($user->name,$abilities)->plainTextToken;

            $response = ResponseMessage::successResponse("Login Successful", ['token'=>$token]);
            return response()->json($response);

        }
    }

    /**
     * logs out a user
     *
     * @return mixed
     */
    public function logout(){
       return auth()->user()->tokens('id',auth()->user()->id)->delete();
    }
}
