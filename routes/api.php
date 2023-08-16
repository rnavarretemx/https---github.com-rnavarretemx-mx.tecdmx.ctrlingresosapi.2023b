<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonalController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/* Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
}); */

Route::controller(PersonalController::class)->prefix("personal")->group(function () {
    Route::get('readall', 'readall');
    
    /* Route::post('create', 'create');
    Route::get('read/{id?}', 'read');
    Route::get('readcsv', 'readcsv');
    Route::get('readmany/{id?}', 'readmany');
    Route::put('update', 'update');
    Route::delete('delete/{id?}', 'delete');
    Route::post('refresh', 'refresh'); */

});
