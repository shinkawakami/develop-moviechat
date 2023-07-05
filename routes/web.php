<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\ChatController;

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
    Route::get('/', [ProfileController::class, 'home'])->name('home');
    
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {

    // Movies Routes
    Route::prefix('moviechat')->group(function () {
        Route::prefix('group')->group(function () {
            Route::get('/create', [GroupController::class, 'create'])->name('group.create');
            Route::post('/create', [GroupController::class, 'store'])->name('group.store');
            Route::get('/list', [GroupController::class, 'index'])->name('group.index');
            
            Route::get('/search', [GroupController::class, 'search'])->name('group.search');
            Route::get('/result', [GroupController::class, 'result'])->name('group.result');
            
            Route::get('/myList', [GroupController::class, 'myList'])->name('group.myList');
    
            Route::post('/{groupId}', [GroupController::class, 'joinGroup'])->name('group.join');
            Route::get('/{groupId}', [GroupController::class, 'show'])->name('group.show');
            
            Route::delete('/{groupId}', [GroupController::class, 'destroy'])->name('group.destroy');
            
            Route::get('/{groupId}/chat', [ChatController::class, 'index'])->name('chat.index');
            Route::post('{groupId}/chat', [ChatController::class, 'sent'])->name('chat.sent');
        });
        
        Route::prefix('movie')->group(function () {
            Route::get('/list', [MovieController::class, 'index'])->name('movie.index');
            Route::get('/search', [MovieController::class, 'search'])->name('movie.search');
            Route::get('/result', [MovieController::class, 'result'])->name('movie.result');
            Route::get('/create', [MovieController::class, 'create'])->name('movie.create');
            Route::post('/create', [MovieController::class, 'store'])->name('movie.store');
            Route::delete('/{movieId}', [MovieController::class, 'destroy'])->name('movie.destroy');
        });
    });
});

require __DIR__.'/auth.php';
