<?php

namespace App\Http\Controllers;

use App\Models\Era;
use App\Models\Genre;
use App\Models\Group;
use App\Models\Message;
use App\Models\Movie;
use App\Models\Platform;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Events\MessageSent;

class MovieController extends Controller
{
    public function index()
    {
        $movies = Movie::all();
        return view('movies.list', compact('movies'));  
    }
    
    public function create()
    {
        $genres = Genre::all();
        $platforms = Platform::all();
        return view('movies.create', compact('genres', 'platforms'));
    }
    
    public function store(Request $request)
    {
        $currentYear = date('Y');

        $validator = Validator::make($request->all(), [
            'movie_title' => 'required|max:20',
            'movie_year' => "nullable|integer|min:1900|max:$currentYear",
        ]);

        $genres = Genre::all();
        $platforms = Platform::all();
        
        if ($validator->fails()) {
            return redirect()->route('movie.create')->with('genres','platforms')->withErrors($validator)->withInput($request->except('password')); 
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
        return view('groups.create', compact('genres', 'platforms', 'eras', 'movies'));
    }
    
    public function search()
    {
        $movies = Movie::all();
        $genres = Genre::all();
        $platforms = Platform::all();
        $eras = Era::all();
        return view('movies.search', compact('movies', 'genres', 'platforms', 'eras'));
    }
    
    public function result(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'movie_title' => 'required|max:20',
        ]);
        
        if ($validator->fails()) {
            $errors = $validator->errors();
        }
        
        if ($request->has('movie_title')) {
            $title = $request->input('movie_title');
            $movies = Movie::where('title', 'like', '%' . $title . '%')->take(5)->get();
            return view('movies.list', compact('movies'));
        }
        if ($request->has('movie_title_id')) {
            $movieId = $request->input('movie_title_id');
            $movie = Movie::findOrFail($movieId);
            return view('movies.list', ['movies' => [$movie]]);
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
        
        return view('movies.list', compact('movies'));
    }
    
    public function destroy($movieId)
    {
        $movie = Movie::findOrFail($movieId);

        $movie->delete();

        // グループ削除後のリダイレクト先などの処理を追加する場合はここに記述

        return redirect()->route('movie.index')->with('success', 'Group deleted successfully.');
    }
}
