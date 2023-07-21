<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\InviteController;
use App\Models\Invite;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['middleware'=>['auth:sanctum']],function(){
    Route::post('/logout',[AuthController::class,'logout']);
    Route::post('/user',[AuthController::class,'getUsers']);
    Route::put('/user',[AuthController::class,'update']);
    Route::delete('/cancelInvite/{id}',[AuthController::class,'cancelInvite']);
    Route::post('/invite',[AuthController::class,'invite']);
    Route::get('/history',[AuthController::class,'getHistory']);
    Route::get('/currentUser',[AuthController::class,'currentUserInvites']);
    Route::apiResource('company',CompanyController::class);
    Route::put('company/{company}', [CompanyController::class, 'update']);
});
Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);
Route::get('/invite/{id}',[InviteController::class,'show']);
Route::put('/invite',[InviteController::class,'update']);
