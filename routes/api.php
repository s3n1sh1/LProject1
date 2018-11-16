<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage as Storage;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('login', 'cAUTH@Login');

Route::middleware(['CheckForToken'])->group(function () {
    Route::get('getData', 'cROUTER@Call');

    Route::get('cobaget', 'cTEST@cobaget'); 
    Route::post('cobapost', 'cTEST@cobapost'); 
});

Route::post('postData', 'cROUTER@Post'); 
Route::post('postFile', 'cUPLOAD@UploadFile');

Route::get('getData2', 'cROUTER@Call2');
Route::get('cobacoba', 'cTEST@CobaCoba');
