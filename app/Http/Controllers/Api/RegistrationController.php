<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Rules\PasswordRule;
use App\Services\ResponseMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegistrationController extends Controller
{

    /**
     * Store a newly created resource in storage.
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
            $response = ResponseMessage::errorResponse("Unable to create New User", $validate->errors());
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


    public function verifyAccount($id)
    {

        $verification =  User::where(['verification_token' =>$id])->first();

        if(!$verification){
            return view('login')->with(['verified'=> "invalid verification link"]);
        }
        else {
            if ($verification->isVerified == false) {
                $user = User::where(['verification_token' => $id])
                    ->update(['isVerified' => true]);
                return view('login')->with(['verified' => "Account verifed success, please login"]);
            }else{
                return view('login')->with(['verified' => "Account Already verified"]);
            }
        }
    }
}
