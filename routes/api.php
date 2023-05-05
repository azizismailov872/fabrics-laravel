<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/auth',function(){
    if(Auth::check()) {
        return response()->json([
            'auth' =>  Auth::check(),
            'user' => Auth::user()
        ],200);
    }

    return response()->json([
        'auth' =>  Auth::check()
    ],200);
});
