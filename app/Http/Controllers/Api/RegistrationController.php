<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Rules\PasswordRule;
use App\Services\ResponseMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Hash;

class RegistrationController extends Controller
{

    /**
     * Creates a new user.
     *
     * Sends a verification email to the user if registration was successful
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {

        // validates the user inputs
        $validate = validator($request->all(),[
            'name' => ['required','string', 'min:3','unique:users'],
            'email' => ['required','email','unique:users'],
            'password' => ['required',new PasswordRule(),'min:6']
        ]);

        // checks if the validation fails
        if ($validate->fails() ){
            $response = ResponseMessage::errorResponse("Bad user input", $validate->errors());
            return response()->json($response, 400);
        }

        // persists the new user to database
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'verification_token' => uniqid('recipe'),
            'password' => Hash::make($request->password)
        ]);

        $response = ResponseMessage::successResponse("User Created Successful, A confirmation email as sent to you", $user);
        return response()->json($response);

    }


    /**
     * verifies a user email when they click on the verification link sent to their email
     *
     * @param $token
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyAccount($token)
    {
        $verification =  User::where(['verification_token' =>$token])->first();

        if(!$verification){
            return response()->json(['verified'=> "invalid verification link"], 404);
        }
        else {
            if ($verification->isVerified == false) {
                $user = User::where(['verification_token' => $token])
                    ->update(['verified_at' => Date::now(), 'isActive' => true]);
                return response()->json(['verified' => "Account verifed success, please login"],200);
            }else{
                return response()->json(['verified' => "Account Already verified"],200);
            }
        }
    }

    /**
     * updates a user password
     * the user must be logged in before the password can be chaanged
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePassword(Request $request){

         $validate  = validator($request->all(),[
             'new_password' => ['required', new PasswordRule(), 'min:6']
         ]);

        // checks if the validation fails
        if ($validate->fails() ){
            $response = ResponseMessage::errorResponse("incorrect format", $validate->errors());
            return response()->json($response, 400);
        }

        $user = User::findOrFail(auth()->user()->id)-first();
        $user->password = Hash::make($request->new_password);
        $user->save();

        $response = ResponseMessage::successResponse("password changed successfully" );
        return response()->json($response, 200);

    }
}
