<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\User;
use App\Services\ResponseMessage;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Profile::where('user_id',auth()->user()->id)->paginate(15);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = validator($request->all(),[
            'first_name' => ['required','string','min:3'],
            'last_name' => ['required','string','min:3'],
            'phone' => ['required','string','min:8','max:13'],
            'profile_picture' => ['required','mimes:png,jpg,jpeg'],
        ]);

        //checks if user input is valid
        if($validator->fails()){
            $response = ResponseMessage::errorResponse("Unable to create profile", $validator->errors());
            return response()->json($response, 400);
        }

        try{
            $profile = User::findOrFail(auth()->user()->id)->profile()->first();
        }catch(NotFoundHttpException $ex){
            $response = ResponseMessage::successResponse($ex->getMessage(), $validator->errors());
            return response()->json($response, $ex->getStatusCode());
        }


//        // check if user profile already exists
        if($profile){
            $response = ResponseMessage::errorResponse("profile already exists");
            return response()->json($response, 409);
        }


        // save the picture
        $pic = auth()->user()->name.uniqid().'.'.$request->file('profile_picture')->extension();

        $profile_pic = $request->file('profile_picture')->storeAs('profile_pictures',$pic);
        // create a new profile

        try{
            $profile = User::findOrFail(auth()->user()->id)->profile()
                ->create([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'phone' => $request->phone,
                    'country' => $request->country,
                    'profile_picture' => $profile_pic
                ]);
        }catch(NotFoundHttpException $ex){
            $response = ResponseMessage::successResponse($ex->getMessage(), $validator->errors());
            return response()->json($response, $ex->getStatusCode());
        }


        if(!$profile){
            $response = ResponseMessage::errorResponse("Unable to create profile", $validator->errors());
            return response()->json($response, 409);
        }
        $response = ResponseMessage::successResponse("Profile created successfully", $validator->errors());
        return response()->json($response, 200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        $validator = validator($request->file(),[
            'profile_picture' => ['required','mimes:png,jpg,jpeg']
        ]);

        //checks if user input is valid
        if($validator->fails()){
            $response = ResponseMessage::errorResponse("Unable to update profile", $validator->errors());
            return response()->json($response, 400);
        }

        try{
            // save the picture
            $pic = auth()->user()->name.uniqid().'.'.$request->file('profile_picture')->extension();
            $profile_pic = $request->file('profile_picture')->storeAs('profile_pictures',$pic);

            $profile = Profile::where('user_id',auth()->user()->id)->first();
            $profile->profile_picture = $profile_pic;
            $profile->save();

            $response = ResponseMessage::successResponse("profile picture changed successfully", $profile);
            return response()->json($response);

        }catch(NotFoundHttpException $ex){
            $response = ResponseMessage::successResponse($ex->getMessage());
            return response()->json($response, $ex->getStatusCode());
        }


    }

}
