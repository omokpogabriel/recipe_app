<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Recipe;
use App\Models\User;
use App\Services\ResponseMessage;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdminController extends Controller
{
    /**
     * get all users registered into the app
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllUsers(){
        $user = User::where('roles','!=','admin')->with('profile')->get();
        return response()->json($user);
    }


    /**
     * get a particular user by user id,  throws a NOTFOUNDException if the recipe is not found
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUser($id){
        try{
        $user = User::findOrFail($id)->where('roles','!=','admin')->with('profile')->get();
            return response()->json($user);
        } catch(NotFoundHttpException $ex){
            return response()->json($ex->getMessage(),$ex->getStatusCode());
        }
    }

    /**
     * Get a particular recipe by id, throws a NOTFOUNDException if the recipe is not found
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRecipe($id){
        try{
            $recipe = Recipe::findOrFail($id)->where('approved',true)->with(['user' => function($query){
                        $query->select(['id','name','email','roles'])->get();
                    }])->get();
            return response()->json($recipe);

        } catch(NotFoundHttpException $ex){
            return response()->json($ex->getMessage(),$ex->getStatusCode());
        }
    }

    /**
     * get all recipes, but approved and not approved
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllRecipe(){
            $recipe = Recipe::with(['user' => function($query){
                $query->select(['id','name','email','roles'])->get();
            }])->get();
                if (!$recipe){
                    return response()->json();
                }
                return response()->json($recipe);
    }


    /**
     * get only approved recipes
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getApprovedRecipe(){
            $recipe = Recipe::where('approved',true)->with(['user' => function($query){
                $query->select(['id','name','email','roles'])->get();
            }])->get();

                if (!$recipe){
                    return response()->json();
                }
             return response()->json($recipe);

    }

    /**
     * Get all unapproved recipes
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUnapprovedRecipe(){
        $recipe = Recipe::where('approved',false)->with(['user' => function($query){
            $query->select(['id','name','email','roles'])->get();
        }])->get();


        if (!$recipe){
            return response()->json();
        }

        return response()->json($recipe);

    }


    /**
     * Authorizea recipe as either approved or not approved
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function authorizeRecipe(Request $request, $id){
        $validator =  validator($request->all(), [
            'approved' => ['required','boolean'],
            'comment' => ['required','string','min:7']
        ]);

        if($validator->fails()){
            $response = ResponseMessage::errorResponse('Invalid data', $validator->errors());
            return response()->json($response, 400);
        }

        try{
            $recipe = Recipe::findOrFail($id);

            if($recipe->approved == true){
                $response = ResponseMessage::errorResponse('recipe already approved');
                return response()->json($response, 409);
            }

            // approves post if $request->approved is true
            if($request->approved == true){
                $recipe->approved = true;
                $recipe->save();
            }else{
                // saves commnets if $request->approved is false
                $recipe->comment()->create([
                   'admin_comment' => 23 //$request->comment
                ]);
                $response = ResponseMessage::successResponse('recipe not approved,', ['comment'=>$request->comment]);
                return response()->json($response);
            }


            $response = ResponseMessage::successResponse('recipe approved', $recipe);
            return response()->json($response);

        } catch(NotFoundHttpException $ex){
            return response()->json($ex->getMessage(),$ex->getStatusCode());
        }

    }


    /**
     * Deletes a recipe by id
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteRecipe($id){

        try{
            $recipe = Recipe::findOrFail($id)->delete();
            $response = ResponseMessage::successResponse('Recipe deleted');
            return response()->json($response);
        } catch(NotFoundHttpException $ex){
            return response()->json($ex->getMessage(),$ex->getStatusCode());
        }
    }


}
