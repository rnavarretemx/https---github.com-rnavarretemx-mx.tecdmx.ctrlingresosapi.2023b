<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonalController;
use App\Http\Controllers\IngresosController;

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

Route::controller(PersonalController::class)->prefix("personal")->group(function () {
    Route::get('readall', 'readall');
});

Route::controller(IngresosController::class)->prefix("ingresos")->group(function () {
    Route::post('create', 'store');
    Route::post('guardar_equipo', 'store_equipo');
    Route::post('guardar_auto', 'store_automovil');
    Route::get('read/{id?}', 'read');
    Route::get('readall', 'readall');
    
});