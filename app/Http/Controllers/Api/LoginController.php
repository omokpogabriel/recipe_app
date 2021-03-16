<?php

namespace App\Http\Controllers;

use App\Events\RegisterEvent;
use App\Models\User;
use App\Models\User as UserAlias;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        auth()->logout();
        return view('login');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request,[
            'email' => ['required','email'],
            'password' => ['required','min:4']
        ]);


        $credential = ['email'=>$request->email,'password'=>$request->password];
            if( auth()->validate($credential) ){
                // check if account is verified
                return view('login')->with(['failed'=>"Invalid Username/password"]);
            }

            $checkVerified = User::where(['email'=>$request->email])->first();
        if($checkVerified->isVerified == false){
            return view('login')->with(['failed'=>"Please verify your email"]);
        }

        if(auth()->attempt($credential)){
            return redirect('/dashboard');
        }

    }

}
