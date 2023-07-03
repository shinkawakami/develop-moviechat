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

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/', [MovieController::class, 'index'])->name('index');

    // Movies Routes
    Route::prefix('movies')->group(function () {
        Route::get('/make', [MovieController::class, 'make'])->name('make');
        Route::post('/make', [MovieController::class, 'store'])->name('store');
        Route::get('/add', [MovieController::class, 'add'])->name('add');
        Route::post('/add', [MovieController::class, 'addMovie'])->name('addMovie');
        Route::get('/showlist', [MovieController::class, 'showlist'])->name('showlist');

        Route::prefix('search')->group(function () {
            Route::get('/group', [MovieController::class, 'searchGroup'])->name('searchGroup');
            Route::get('/group/result', [MovieController::class, 'resultGroup'])->name('resultGroup');
            Route::get('/movie', [MovieController::class, 'searchMovie'])->name('searchMovie');
            Route::get('/movie/result', [MovieController::class, 'resultMovie'])->name('resultMovie');
            Route::get('/result', [MovieController::class, 'result'])->name('result');
        });

        Route::prefix('groups')->group(function () {
            Route::post('/{groupId}', [MovieController::class, 'joinGroup'])->name('joinGroup');
            Route::get('/{groupId}', [MovieController::class, 'showGroup'])->name('showGroup');
            Route::get('/{groupId}/chat', [MovieController::class, 'chat'])->name('chat');
            Route::post('/{groupId}/chat', [MovieController::class, 'sendMessage'])->name('sendMessage');
        });
    });
});

require __DIR__.'/auth.php';
