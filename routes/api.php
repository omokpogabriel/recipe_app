<?php

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\RecipeController;
use App\Http\Controllers\Api\RegistrationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::group(['prefix'=>'/v1'], function(){
   Route::post('/register', [RegistrationController::class,'register'])->name('register');
   Route::get('/verify_account/{token}', [RegistrationController::class,'verifyAccount']);
   Route::get('/login', [LoginController::class, 'login'])->name('login');
   Route::post('/logout', [LoginController::class, 'logout'])
       ->middleware('auth:sanctum');
    Route::get('/recipes',[RecipeController::class, 'index']);
    Route::get('/recipes/recipe/{id}',[RecipeController::class, 'show']);
    Route::get('/recipes/search',[RecipeController::class, 'searchRecipe']);


    Route::group(['middleware'=>'auth:sanctum','prefix'=>'/profile'], function(){
        Route::get('/',[ProfileController::class, 'index']);
        Route::post('/create',[ProfileController::class, 'store']);
        Route::post('/update',[ProfileController::class, 'update']);
    });

    Route::group(['middleware'=>'auth:sanctum','prefix'=>'/recipes'], function(){
            Route::get('/', [RecipeController::class, 'index']);
            Route::post('/postrecipe', [RecipeController::class, 'postStatus']);
            Route::post('/update/{id}',[RecipeController::class, 'update']);
            Route::delete('/delete/{id}',[RecipeController::class, 'destroy']);
    });

    Route::group(['middleware'=>['auth.admin', 'auth:sanctum'], 'prefix'=>'admin'], function(){
            Route::get('/users', [AdminController::class, 'getAllUsers'] );
            Route::get('/users/{id}', [AdminController::class, 'getUser'] );
            Route::get('/recipes/status/approved', [AdminController::class, 'getApprovedRecipe'] );
            Route::get('/recipes/status/unapproved', [AdminController::class, 'getUnapprovedRecipe'] );
            Route::post('/recipes/{id}/authorize', [AdminController::class, 'authorizeRecipe'] );
            Route::delete('/users/{id}/delete', [AdminController::class, 'deleteUser'] ); // not done
            Route::get('/recipes/{id}', [AdminController::class, 'getRecipe'] );
            Route::get('/recipes', [AdminController::class, 'getAllRecipe'] );
    });

});
