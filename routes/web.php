<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;

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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function(){
    Route::get('/', [MovieController::class, 'index'])->name('index');
    Route::post('/movies/make', [MovieController::class, 'store'])->name('store');
    Route::get('/movies/make', [MovieController::class, 'make'])->name('make');
    Route::get('/movies/search', [MovieController::class, 'search'])->name('search');
    Route::get('/movies/showlist', [MovieController::class, 'showlist'])->name('showlist');
    Route::post('/movies/groups/{group}', [MovieController::class, 'joinGroup'])->name('joinGroup');
    Route::get('/movies/groups/{group}', [MovieController::class, 'showGroup'])->name('showGroup');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
