<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;

class MovieController extends Controller
{
    public function index(Movie $movies)
    {
        return view('movies.index')->with(['pages' => $movies->getByLimit()]);  
       //blade内で使う変数'pages'と設定。'pages'の中身にgetを使い、インスタンス化した$moiesを代入。
    }
    
    public function make(Movie $movies)
    {
        return view('movies.make');
    }
    
    public function search(Movie $movies)
    {
        return view('movies.search');
    }
    
    public function showlist(Movie $movies)
    {
        return view('movies.showlist');
    }
}
