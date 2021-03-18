<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Recipe;
use App\Models\User;
use App\Services\ResponseMessage;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Recipe::orderBy('id','desc')
            ->with(['user'=>function($query){
            $query->select('name','email','id');
        }])
            ->paginate();
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postStatus(Request $request)
    {
        $validator = validator($request->all(),[
            'recipe_name' => ['required', 'string', 'min:3'],
            'title' => ['required', 'string', 'min:5'],
            'description' => ['required', 'string', 'min:20'],
            'recipe_picture' => ['required', 'mimes:png,jpg,jpeg'],
            'ingredients' => ['required', 'string', 'min:20'],
            'nutritional_value' => ['required', 'string', 'min:5'],
            'cost' => ['required'],
            'primary_ingredients' => ['required', 'string', 'min:5'],
            'main_ingredients' => ['required', 'string', 'min:5'],
            'meal' => ['required', 'string', 'min:2']
        ]);

        if($validator->fails()){
            $response = ResponseMessage::errorResponse("Invalid format", $validator->errors());
            return response()->json($response, 400);
        }

            $recipe_pic = $request->file('recipe_picture')
                ->storeAs('recipes_img', uniqid(auth()->user()->name).'.'.$request->file('recipe_picture')
                          ->extension() );
            $recipe = User::findOrFail(auth()->user()->id)->recipes()->create([
                'recipe_name' =>$request->recipe_name,
                'title' => $request->title,
                'description' => $request->description,
                'ingredients' => $request->ingredients,
                'nutritional_value' => $request->nutritional_value,
                'cost' => $request->cost,
                'recipe_picture' => $recipe_pic,
                'primary_ingredients' => $request->primary_ingredients,
                'main_ingredients' => $request->main_ingredients,
                'meal' => $request->meal
            ]);

            $response = ResponseMessage::successResponse("Recipe was submitted successfully");
            return response()->json($response);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try{
                return Recipe::findOrFail($id)
                    ->with(['user'=>function($query){
                        $query->select('name','email','id')->with('profile');
                    }])
                ->get();
        }catch(NotFoundHttpException $ex){
            $response = ResponseMessage::errorResponse($ex->getMessage());
            return response()->json($response, $ex->getStatusCode());
        }
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

        $validator = validator($request->all(),[
            'recipe_name' => ['required', 'string', 'min:3'],
            'title' => ['required', 'string', 'min:5'],
            'description' => ['required', 'string', 'min:20'],
            'recipe_picture' => ['required', 'mimes:png,jpg,jpeg'],
            'ingredients' => ['required', 'string', 'min:20'],
            'nutritional_value' => ['required', 'string', 'min:5'],
            'cost' => ['required'],
            'primary_ingredients' => ['required', 'string', 'min:5'],
            'main_ingredients' => ['required', 'string', 'min:5'],
            'meal' => ['required', 'string', 'min:2']
        ]);

        if($validator->fails()){
            $response = ResponseMessage::errorResponse("Invalid format", $validator->errors());
            return response()->json($response, 400);
        }


            $recipe = Recipe::where(['user_id'=> auth()->user()->id,'id'=>$id])->first();

        if(!$recipe){
            $response = ResponseMessage::errorResponse("Recipe not found");
            return response()->json($response, 404);
        }

        if($recipe->approved == true){
            $response = ResponseMessage::errorResponse("Approved recipe cannot be edited");
            return response()->json($response, 204);
        }

        $recipe_pic = $request->file('recipe_picture')
            ->storeAs('recipes_img', uniqid(auth()->user()->name).'.'.$request->file('recipe_picture')
                    ->extension() );

                $recipe->recipe_name =$request->recipe_name;
                $recipe->title = $request->title;
                $recipe->description = $request->description;
                $recipe->ingredients = $request->ingredients;
                $recipe->nutritional_value = $request->nutritional_value;
                $recipe->cost = $request->cost;
                $recipe->recipe_picture = $recipe_pic;
                $recipe->primary_ingredients = $request->primary_ingredients;
                $recipe->main_ingredients = $request->main_ingredients;
                $recipe->meal = $request->meal;
                $recipe->save();

        $response = ResponseMessage::successResponse("Recipe was updated successfully",$recipe);
        return response()->json($response);

    }

    public function searchRecipe(Request $request){

        $validator = validator($request->all(),[
            'search' => ['required', 'string', 'min:3'],

        ]);

        if($validator->fails()){
            $response = ResponseMessage::errorResponse("Invalid format", $validator->errors());
            return response()->json($response, 400);
        }

        $recipes = Recipe::where(['approved'=>true])
            ->where(function($builder) use ($request){
                    $builder->where('title','LIKE','%'.$request->search.'%')
                        ->orWhere('recipe_name','LIKE','%'.$request->search.'%');
            })
            ->paginate(15);

        if(!$recipes){
            $response = ResponseMessage::errorResponse("Recipe not found");
            return response()->json($response, 404);
        }

        return response()->json($recipes);

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {


        try{
            $recipe = Recipe::where(['user_id'=> auth()->user()->id,'id'=>$id])->first();
        }catch(NotFoundHttpException $ex){
            $response = ResponseMessage::errorResponse($ex->getMessage());
            return response()->json($response, $ex->getStatusCode());
        }

        if(!$recipe){
            $response = ResponseMessage::errorResponse("Recipe not found");
            return response()->json($response, 404);
        }

        if($recipe->approved == true){
            $response = ResponseMessage::errorResponse("Approved recipe cannot be deleted");
            return response()->json($response, 204);
        }

        $recipe->delete();

        $response = ResponseMessage::successResponse("Recipe was deleted successfully",$recipe);
        return response()->json($response);

    }
}
