<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\PostController;

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

    Route::prefix('moviechat')->group(function () {
        Route::prefix('groups')->group(function () {
            Route::get('/', [GroupController::class, 'index'])->name('groups.index');
            Route::get('/create', [GroupController::class, 'create'])->name('groups.create');
            Route::post('/create', [GroupController::class, 'store'])->name('groups.store');
            
            Route::get('/search', [GroupController::class, 'showSearch'])->name('groups.showSearch');
            Route::get('/search-results', [GroupController::class, 'searchResults'])->name('groups.searchResults');
            
            Route::get('/myList', [GroupController::class, 'myList'])->name('groups.myList');
    
            Route::post('/{groupId}', [GroupController::class, 'joinGroup'])->name('groups.join');
            Route::get('/{groupId}', [GroupController::class, 'show'])->name('groups.show');
            
            Route::delete('/{groupId}', [GroupController::class, 'destroy'])->name('groups.destroy');
            
            Route::get('/{groupId}/chat', [ChatController::class, 'index'])->name('chat.index');
            Route::post('/{groupId}/chat', [ChatController::class, 'sent'])->name('chat.sent');
            Route::get('/{groupId}/leave', [ChatController::class, 'leave'])->name('groups.leave');
            Route::post('/{groupId}/request', [ChatController::class, 'request'])->name('view.request');
            Route::post('/{groupId}/approve/{viewGroupId}', [ChatController::class, 'approve'])->name('view.approve');
            Route::post('/{groupId}/cancel/{viewGroupId}', [ChatController::class, 'cancel'])->name('view.cancel');
            Route::get('/{groupId}/view/{viewGroupId}', [ChatController::class, 'view'])->name('view.index');
            Route::post('/{groupId}/view/{viewGroupId}', [ChatController::class, 'viewChat'])->name('view.chat');
        });
        
        Route::prefix('movies')->group(function () {
            Route::get('/list', [MovieController::class, 'index'])->name('movies.index');
            Route::get('/search', [MovieController::class, 'search'])->name('movies.search');
            Route::post('/search', [MovieController::class, 'select'])->name('movies.select');
            Route::post('/unselect', [MovieController::class, 'unselect'])->name('movies.unselect');
            Route::get('/result', [MovieController::class, 'result'])->name('movies.result');
            Route::get('/create', [MovieController::class, 'create'])->name('movies.create');
            Route::post('/create', [MovieController::class, 'store'])->name('movies.store');
            Route::delete('/{movieId}', [MovieController::class, 'destroy'])->name('movies.destroy');
        });
        
        Route::prefix('posts')->group(function () {
            Route::get('/', [PostController::class, 'index'])->name('posts.index');
            Route::get('search', [PostController::class, 'search'])->name('posts.search');
            Route::get('create', [PostController::class, 'create'])->name('posts.create');
            Route::post('/', [PostController::class, 'store'])->name('posts.store');
            Route::get('user', [PostController::class, 'user'])->name('posts.user');
            Route::get('{post}', [PostController::class, 'show'])->name('posts.show');
            Route::get('{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
            Route::put('{post}', [PostController::class, 'update'])->name('posts.update');
            Route::delete('{post}', [PostController::class, 'destroy'])->name('posts.destroy');
            Route::post('{post}/comment', [PostController::class, 'comment'])->name('posts.comment');
        });
    });
});

require __DIR__.'/auth.php';
