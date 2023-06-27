<?php

namespace App\Http\Controllers;

use App\Http\Requests\MovieGroupRequest;
use App\Models\Movie;
use App\Models\Group;
use App\Models\Genre;
use App\Models\Subscription;
use App\Models\User;
use App\Models\GroupUser;
use Illuminate\Support\Facades\Auth;

class MovieController extends Controller
{
    public function index(Movie $movie)
    {
        return view('movies.index');  
    }
    
    public function make(Movie $movie)
    {
        return view('movies.make');
    }
    
    public function search(Movie $movie)
    {
        return view('movies.search');
    }
    
    public function showlist(Movie $movie)
    {
        $groups = Group::all();
        return view('movies.showlist', compact('groups'));
    }
    
    public function store(MovieGroupRequest $request)
    {
        // リクエストデータを取得
        $validatedData = $request->validated();
        
        // Genreモデルの作成と保存
        $genre = new Genre();
        $genre->name = $validatedData['genre']['name'];
        $genre->save();
    
        // Subscriptionモデルの作成と保存
        $subscription = new Subscription();
        $subscription->name = $validatedData['subscription']['name'];
        $subscription->save();
        
        // Movieモデルの作成と保存
        $movie = new Movie();
        $movie->genre()->associate($genre);
        $movie->subscription()->associate($subscription);
        $movie->title = $validatedData['movie']['title'];
        $movie->released_at = $validatedData['movie']['released_at'];
        $movie->save();
    
        // Groupモデルの作成と保存
        $group = new Group();
        $group->created_id = $validatedData['group']['created_id'];
        $group->movie()->associate($movie);
        $group->name = $validatedData['group']['name'];
        $group->capacity = $validatedData['group']['capacity'];
        $group->save();
        
        $user = Auth::user();
        $group->users()->attach($user);

        // 成功時の処理（例: 成功メッセージを表示してリダイレクト）
        return redirect()->route('showlist');
    }
    
    public function joinGroup($groupId)
    {
        // ログインしているユーザーを取得
        $user = Auth::user();
        
        // グループを取得
        $group = Group::findOrFail($groupId);
        
        // ユーザーをグループに参加させる
        $group->users()->attach($user);
        
        // 成功時の処理（例: 成功メッセージを表示してリダイレクト）
        return view('movies.index');  
    }
    
    public function showGroup(Group $group)
    {
        return view('groups.index', compact('group'));
    }
}
