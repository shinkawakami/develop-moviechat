<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ViewingController;

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
    Route::get('/dashboard', [ProfileController::class, 'home'])->name('dashboard');

    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
        Route::get('/{user}', [ProfileController::class, 'show'])->name('profile.show');
        Route::post('/follow/{user}', [ProfileController::class, 'follow'])->name('profile.follow');
        Route::post('/unfollow/{user}', [ProfileController::class, 'unfollow'])->name('profile.unfollow');
    });
});

Route::middleware(['auth'])->group(function () {
    Route::prefix('moviechat')->group(function () {
        Route::prefix('groups')->group(function () {
            Route::get('/', [GroupController::class, 'index'])->name('groups.index');
            Route::post('/', [GroupController::class, 'store'])->name('groups.store');
            Route::get('/create', [GroupController::class, 'create'])->name('groups.create');
            Route::get('/search', [GroupController::class, 'showSearch'])->name('groups.showSearch');
            Route::get('/search-results', [GroupController::class, 'searchResults'])->name('groups.searchResults');
            Route::get('/user', [GroupController::class, 'user'])->name('groups.user');
            
            Route::prefix('{group}')->group(function () {
                Route::get('/', [GroupController::class, 'show'])->name('groups.show');
                Route::put('/', [GroupController::class, 'update'])->name('groups.update');
                Route::delete('/', [GroupController::class, 'destroy'])->name('groups.destroy');
                Route::post('/join', [GroupController::class, 'join'])->name('groups.join');
                Route::get('/leave', [GroupController::class, 'leave'])->name('groups.leave');
                Route::get('/edit', [GroupController::class, 'edit'])->name('groups.edit');
                Route::delete('/remove/{user}', [GroupController::class, 'removeUser'])->name('groups.removeUser');

                Route::prefix('chats')->group(function () {
                    Route::get('/', [ChatController::class, 'index'])->name('chats.index');
                    Route::post('/', [ChatController::class, 'send'])->name('chats.send');
                    Route::get('/receive', [ChatController::class, 'receive'])->name('chats.receive');
                    Route::delete('/{message}', [ChatController::class, 'destroy'])->name('chats.destroy');
                });
    
                Route::prefix('viewings')->group(function () {
                    Route::post('/request', [ViewingController::class, 'request'])->name('viewings.request');
                    Route::prefix('{viewing}')->group(function () {
                        Route::get('/', [ViewingController::class, 'index'])->name('viewings.index');
                        Route::post('/', [ViewingController::class, 'chat'])->name('viewings.chat');
                        Route::post('/approve', [ViewingController::class, 'approve'])->name('viewings.approve');
                        Route::post('/cancel', [ViewingController::class, 'cancel'])->name('viewings.cancel');
                        Route::delete('/{message}', [ViewingController::class, 'destroy'])->name('viewings.destroy');
                    });
                });
            });
        });
        
        Route::prefix('movies')->group(function () {
            Route::get('/', [MovieController::class, 'index'])->name('movies.index');
            Route::get('/search', [MovieController::class, 'search'])->name('movies.search');
            Route::get('/{movie}', [MovieController::class, 'show'])->name('movies.show');
        });
        
        Route::prefix('posts')->group(function () {
            Route::get('/', [PostController::class, 'index'])->name('posts.index');
            Route::post('/', [PostController::class, 'store'])->name('posts.store');
            Route::get('search', [PostController::class, 'search'])->name('posts.search');
            Route::get('create', [PostController::class, 'create'])->name('posts.create');
            Route::get('user', [PostController::class, 'user'])->name('posts.user');
            
            Route::prefix('{post}')->group(function () {
                Route::put('/', [PostController::class, 'update'])->name('posts.update');
                Route::get('/', [PostController::class, 'show'])->name('posts.show');
                Route::delete('/', [PostController::class, 'destroy'])->name('posts.destroy');
                Route::get('/edit', [PostController::class, 'edit'])->name('posts.edit');
                Route::post('/comment', [PostController::class, 'comment'])->name('posts.comment');
            });
        });
    });
});

require __DIR__.'/auth.php';
