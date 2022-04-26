<?php

use App\Http\Controllers\NeoController;
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

Route::get('/',[NeoController::class,'index'])->name('neo');

Route::post('collectdate',[NeoController::class,'submit'])->name('collectdate');

// Route::get('users',[UserController::class,'index'])->name('users');