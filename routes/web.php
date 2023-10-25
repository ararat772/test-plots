<?php

use App\Http\Controllers\WEB\WebController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/plots', [WebController::class, 'show'])->name('plots');

Route::get('/', function () {
    return view('plots');
});
