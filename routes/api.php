<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\UserController;


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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::controller(AuthController::class)->group(function(){
   Route::post('login','login');
   Route::post('logout','logout');
   Route::post('register','createUser');
   Route::post('refresh', 'refresh');   
   Route::delete('deleteUser', 'deleteUser');   
});
Route::controller(ProjectController::class)->group(function(){
    Route::post('createProject','createProject');
    Route::put('updateProject','updateProject');
    Route::delete('deleteProject','deleteProject');
 });
 Route::controller(UserController::class)->group(function(){
    Route::get('users','getAllUsers');
 });
