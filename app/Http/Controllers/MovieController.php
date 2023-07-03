<?php

namespace App\Http\Controllers;

use App\Http\Requests\MovieGroupRequest;
use App\Http\Requests\MessageRequest;
use App\Models\Era;
use App\Models\Genre;
use App\Models\Group;
use App\Models\Message;
use App\Models\Movie;
use App\Models\Platform;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\MessageSent;

class MovieController extends Controller
{
    public function index(Movie $movie)
    {
        return view('movies.index');  
    }
    
    public function make(Movie $movie)
    {
        $movies = Movie::all();
        $genres = Genre::all();
        $platforms = Platform::all();
        $eras = Era::all();
        return view('movies.make', compact('movies', 'genres', 'platforms', 'eras'));
    }
    
    public function add(Movie $movie)
    {
        $genres = Genre::all();
        $platforms = Platform::all();
        return view('movies.add', compact('genres', 'platforms'));
    }
    
    public function addMovie(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'movie_title' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('/movies/add')
                ->withErrors($validator)
                ->withInput();
        }

        $title = $request->input('movie_title');
        $genres = $request->input('movie_genre_id');
        $platforms = $request->input('movie_platform_id');
        $year = $request->input('movie_year');

        // 映画作成
        $movie = new Movie();
        $movie->title = $title;
        
        if (!empty($year)) {
            $movie->year = $year;
            // 公開年度の関連付け
            $eraYear = substr($year, 0, 3);
            $era = Era::where('era', 'LIKE', $eraYear.'0年代')->first();
            if ($era) {
                $movie->era()->associate($era);
            } else {
                // error 
            }
        }
        $movie->save();

        // ジャンルの関連付け
        if (!empty($genres)) {
            $movie->genres()->attach($genres);
        }

        // プラットフォームの関連付け
        if (!empty($platforms)) {
            $movie->platforms()->attach($platforms);
        }

        $genres = Genre::all();
        $platforms = Platform::all();
        $eras = Era::all();
        $movies = Movie::all();
        return view('movies.make', compact('genres', 'platforms', 'eras', 'movies'));
    }
    
    public function searchGroup(Movie $movie)
    {
        $genres = Genre::all();
        $platforms = Platform::all();
        $eras = Era::all();
        return view('movies.search', compact('genres', 'platforms', 'eras'));
    }
    
    public function showlist(Movie $movie)
    {
        $groups = Group::all();
        return view('movies.showlist', compact('groups'));
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'group_name' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
        }
        
        $groupName = $request->input('group_name');
        $capacity = $request->input('group_capacity');
        $titles = $request->input('group_movie_title_id');
        $genres = $request->input('group_movie_genre_id');
        $platforms = $request->input('group_movie_platform_id');
        $eras = $request->input('group_movie_era_id');
        
        $user = Auth::user();
        
        // Groupモデルの作成と保存
        $group = new Group();
        $group->name = $groupName;
        $group->creator()->associate($user);
        $group->capacity = $capacity;
        $group->save();
        
        $group->users()->attach($user);
        $group->movies()->attach($titles);
        
        // ジャンルの関連付け
        if (!empty($genres)) {
            $group->genres()->attach($genres);
        }

        // プラットフォームの関連付け
        if (!empty($platforms)) {
            $group->platforms()->attach($platforms);
        }
        
        // 年代の関連付け
        if (!empty($eras)) {
            $group->eras()->attach($eras);
        }
        
        // 成功時の処理
        $groups = Group::all();
        return view('movies.showlist', compact('groups'));
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
        return redirect()->route('chat', ['groupId' => $groupId]);
    }
    
    public function showGroup($groupId)
    {
        $group = Group::findOrFail($groupId);
        return view('groups.index', compact('group'));
    }
    
    public function chat($groupId)
    {
        $group = Group::findOrFail($groupId);
        return view('groups.chat', compact('group'));
    }
    
    public function sendMessage(Request $request, $groupId)
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required',
        ]);
    
        $user = $request->user();
        $group = Group::findOrFail($groupId);
    
        $chatMessage = new Message();
        $chatMessage->content = $request->input('message');
        $chatMessage->user()->associate($user);
        $chatMessage->group()->associate($group);
        $chatMessage->save();
    
        event(new MessageSent($chatMessage)); // メッセージ送信イベントを発行
    
        return redirect()->back();
    }
    
    public function searchMovie()
    {
        $movies = Movie::all();
        $genres = Genre::all();
        $platforms = Platform::all();
        $eras = Era::all();
        return view('movies.search_movie', compact('movies', 'genres', 'platforms', 'eras'));
    }
    
    public function resultMovie(Request $request)
    {
        if ($request->has('movie_title')) {
            $title = $request->input('movie_title');
            $movies = Movie::where('title', 'like', '%' . $title . '%')->take(5)->get();
            return view('movies.result', compact('movies'));
        }
        if ($request->has('movie_title_id')) {
            $movieId = $request->input('movie_title_id');
            $movie = Movie::findOrFail($movieId);
            return view('movies.result', ['movies' => [$movie]]);
        }
        
        $movies = Movie::query();

        if ($request->has('movie_era_id')) {
            $eraId = $request->input('movie_era_id');
            if (!empty($eraId)) {
                $movies->whereHas('era', function ($query) use ($eraId) {
                    $query->where('era_id', $eraId);
                });
            }
        }
        
        if ($request->has('movie_genre_id')) {
            $genreIds = $request->input('movie_genre_id');
            $movies->where(function ($query) use ($genreIds) {
                foreach ($genreIds as $genreId) {
                    if (!empty($genreId)) {
                        $query->whereHas('genres', function ($subQuery) use ($genreId) {
                            $subQuery->where('genre_id', $genreId);
                        });
                    }
                }
            });
        }
        
        if ($request->has('movie_platform_id')) {
            $platformIds = $request->input('movie_platform_id');
            $movies->where(function ($query) use ($platformIds) {
                foreach ($platformIds as $platformId) {
                    if (!empty($platformId)) {
                        $query->whereHas('platforms', function ($subQuery) use ($platformId) {
                            $subQuery->where('platform_id', $platformId);
                        });
                    }
                }
            });
        }
        
        $movies = $movies->get();
        
        return view('movies.result', compact('movies'));
    }
    
    public function resultGroup(Request $request)
    {
        if ($request->has('group_name')) {
            $name = $request->input('group_name');
            $groups = Group::where('name', 'like', '%' . $name . '%')->take(5)->get();
            return view('movies.showlist', compact('groups'));
        }
        
        $groups = Group::query();

        if ($request->has('group_movie_era_id')) {
            $eraIds = $request->input('group_movie_era_id');
            $groups->where(function ($query) use ($eraIds) {
                foreach ($eraIds as $eraId) {
                    if (!empty($eraId)) {
                        $query->whereHas('eras', function ($subQuery) use ($eraId) {
                            $subQuery->where('era_id', $eraId);
                        });
                    }
                }
            });
        }
        
        if ($request->has('group_movie_genre_id')) {
            $genreIds = $request->input('group_movie_genre_id');
            $groups->where(function ($query) use ($genreIds) {
                foreach ($genreIds as $genreId) {
                    if (!empty($genreId)) {
                        $query->whereHas('genres', function ($subQuery) use ($genreId) {
                            $subQuery->where('genre_id', $genreId);
                        });
                    }
                }
            });
        }
        
        if ($request->has('group_movie_platform_id')) {
            $platformIds = $request->input('group_movie_platform_id');
            $groups->where(function ($query) use ($platformIds) {
                foreach ($platformIds as $platformId) {
                    if (!empty($platformId)) {
                        $query->whereHas('platforms', function ($subQuery) use ($platformId) {
                            $subQuery->where('platform_id', $platformId);
                        });
                    }
                }
            });
        }
        
        $groups = $groups->get();
        
        return view('movies.showlist', compact('groups'));
    }
}
