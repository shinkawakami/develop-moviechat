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
            
            Route::get('/my-groups', [GroupController::class, 'myGroups'])->name('groups.myGroups');
    
            Route::post('/{group}/join', [GroupController::class, 'join'])->name('groups.join');
            
            Route::get('/{group}', [GroupController::class, 'show'])->name('groups.show');
            
            Route::delete('/{group}', [GroupController::class, 'destroy'])->name('groups.destroy');
            
            Route::get('/{group}/chat', [ChatController::class, 'index'])->name('chat.index');
            Route::post('/{group}/chat', [ChatController::class, 'sent'])->name('chat.sent');
            Route::get('/{group}/leave', [ChatController::class, 'leave'])->name('groups.leave');
            Route::post('/{group}/request', [ChatController::class, 'request'])->name('view.request');
            Route::post('/{group}/approve/{viewGroup}', [ChatController::class, 'approve'])->name('view.approve');
            Route::post('/{group}/cancel/{viewGroup}', [ChatController::class, 'cancel'])->name('view.cancel');
            Route::get('/{group}/view/{viewGroup}', [ChatController::class, 'view'])->name('view.index');
            Route::post('/{group}/view/{viewGroup}', [ChatController::class, 'viewChat'])->name('view.chat');
        });
        
        Route::prefix('movies')->group(function () {
            Route::get('/', [MovieController::class, 'index'])->name('movies.index');
            Route::get('/search', [MovieController::class, 'search'])->name('movies.search');
            Route::post('/search', [MovieController::class, 'select'])->name('movies.select');
            Route::post('/unselect', [MovieController::class, 'unselect'])->name('movies.unselect');
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
