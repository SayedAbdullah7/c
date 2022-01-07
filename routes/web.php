<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group([
    'middleware' => ['token'],
], function ($router) {
        Route::get('units',[PageController::class,'show'])->name('units');
        // Route::get('trips',[PageController::class,'api'])->name('trips');
        // Route::get('api',[PageController::class,'api'])->name('api');

});

// Route::get('units',[UnitController::class,'show'])->name('units');
Route::get('login',[LoginController::class,'create'])->name('login');
Route::post('login',[LoginController::class,'store'])->name('login');
Route::post('logout',[LoginController::class,'destroy'])->name('logout');





// Route::get('/', function () {
//     return view('welcome');
// });
