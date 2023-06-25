<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;
use App\Models\Group;

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
    
    public function store(Group $group, Request $request)
    {
        $input = $request['group'];
        // グループの作成
        
        $group->fill($input);
        $group->created_id = 3;
        $group->save();

        // 成功時の処理（例: 成功メッセージを表示してリダイレクト）
        return redirect('/movies/showlist');
    }
}
