<?php

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
   Route::post('/register', [RegistrationController::class,'register']);
   Route::get('/verify_account/{token}', [RegistrationController::class,'verifyAccount']);
   Route::get('/login', [LoginController::class, 'login']);
   Route::post('/logout', [LoginController::class, 'logout'])
       ->middleware('auth:sanctum');
    Route::get('/recipes',[RecipeController::class, 'index']);


    Route::group(['middleware'=>'auth:sanctum','prefix'=>'/profile'], function(){
        Route::get('/',[ProfileController::class, 'index']);
        Route::post('/create',[ProfileController::class, 'store']);
        Route::post('/update',[ProfileController::class, 'update']);
    });

    Route::group(['middleware'=>'auth:sanctum','prefix'=>'/recipe'], function(){

    });
});
