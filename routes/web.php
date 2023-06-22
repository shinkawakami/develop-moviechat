<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;

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

Route::get('/', [MovieController::class, 'index']);

Route::get('/movies/make', [MovieController::class ,'make']);

Route::get('/movies/search', [MovieController::class ,'search']);

Route::get('/movies/showlist', [MovieController::class ,'showlist']);

