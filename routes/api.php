<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Colors\ColorsController;
use App\Http\Controllers\Fabrics\FabricsController;
use App\Http\Controllers\Materials\MaterialsController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

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

Route::get('/auth',[AuthenticatedSessionController::class,'auth']);


Route::controller(FabricsController::class)->group(function(){
    Route::get('/fabrics','get');
    Route::get('/fabrics/{id}','one');
    Route::post('/fabrics/create','create');
    Route::post('/fabrics/update/{id}','update');
    Route::get('/fabrics/delete/{id}','delete');
    Route::post('/fabrics/delete','destroy');
});

Route::controller(ColorsController::class)->group(function(){
    Route::get('/colors','get');
    Route::get('/colors/list','list');
    Route::get('/colors/{id}','one');
    Route::post('/colors/create','create');
    Route::post('/colors/update/{id}','update');
    Route::get('/colors/delete/{id}','delete');
    Route::post('/colors/delete','destroy');
});

Route::controller(MaterialsController::class)->group(function(){
    Route::get('/materials','get');
    Route::get('/materials/list','list');
    Route::get('/materials/{id}','one');
    Route::post('/materials/create','create');
    Route::post('/materials/update/{id}','update');
    Route::get('/materials/delete/{id}','delete');
    Route::post('/materials/delete','destroy');
});