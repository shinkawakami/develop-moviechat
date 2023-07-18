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
            
            Route::post('/{group}/users/{user}/remove', [GroupController::class, 'removeUser'])->name('groups.removeUser');
            Route::get('/{group}/leave', [GroupController::class, 'leave'])->name('groups.leave');
            
            Route::get('/{group}/edit', [GroupController::class, 'edit'])->name('groups.edit');
            Route::put('/{group}/update', [GroupController::class, 'update'])->name('groups.update');
            
            Route::delete('/{group}', [GroupController::class, 'destroy'])->name('groups.destroy');
            
            Route::get('/{group}/chats', [ChatController::class, 'index'])->name('chats.index');
            Route::post('/{group}/chats', [ChatController::class, 'send'])->name('chats.send');
            Route::delete('{group}/chats/{message}', [ChatController::class, 'destroy'])->name('chats.destroy');
             
            Route::post('/{group}/viewings/request', [ViewingController::class, 'request'])->name('viewings.request');
            Route::post('/{group}/viewings/{viewing}/approve', [ViewingController::class, 'approve'])->name('viewings.approve');
            Route::post('/{group}/viewings/{viewing}/cancel', [ViewingController::class, 'cancel'])->name('viewings.cancel');
            
            Route::get('/{group}/viewings/{viewing}', [ViewingController::class, 'index'])->name('viewings.index');
            Route::post('/{group}/viewings/{viewing}/chats', [ViewingController::class, 'chat'])->name('viewings.chat');
        });
        
        Route::prefix('movies')->group(function () {
            Route::get('/', [MovieController::class, 'index'])->name('movies.index');
            Route::get('/search', [MovieController::class, 'search'])->name('movies.search');
            Route::get('/{movie}', [MovieController::class, 'show'])->name('movies.show');
            Route::get('/{movie}/details', [MovieController::class, 'details'])->name('movies.details');
        });
        
        Route::prefix('posts')->group(function () {
            Route::get('/', [PostController::class, 'index'])->name('posts.index');
            Route::get('search', [PostController::class, 'search'])->name('posts.search');
            Route::get('create', [PostController::class, 'create'])->name('posts.create');
            Route::post('/', [PostController::class, 'store'])->name('posts.store');
            Route::get('/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
            Route::put('/{post}', [PostController::class, 'update'])->name('posts.update');
            Route::get('user', [PostController::class, 'user'])->name('posts.user');
            Route::get('{post}', [PostController::class, 'show'])->name('posts.show');
            
            Route::delete('{post}', [PostController::class, 'destroy'])->name('posts.destroy');
            Route::post('{post}/comment', [PostController::class, 'comment'])->name('posts.comment');
        });
    });
});

require __DIR__.'/auth.php';
