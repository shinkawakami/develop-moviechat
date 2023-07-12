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
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Events\MessageSent;

class MovieController extends Controller
{
    private function getMovieDetails($movieId)
    {
        $apiKey = config('tmdb.api_key');
        $response = Http::get("https://api.themoviedb.org/3/movie/{$movieId}?api_key={$apiKey}&language=ja-JP");
        return $response->json();
    }
    
    private function searchMovies($query, $page = 1)
    {
        $apiKey = config('tmdb.api_key');
        $response = Http::get("https://api.themoviedb.org/3/search/movie?api_key={$apiKey}&query={$query}&language=ja-JP&page={$page}");
        $results = $response->json();
        
        // total_pagesとtotal_resultsを含むレスポンスを返すように変更します
        return [
            'results' => $results['results'] ?? [],
            'total_results' => $results['total_results'] ?? 0,
            'results_per_page' => 20,
        ];
    }
    
    public function search(Request $request)
    {
        $query = $request->get('movie_title');
        $page = $request->get('page', 1);

        // TMDB APIを使用して映画を検索します
        $movies = $this->searchMovies($query, $page);
        
        // LaravelのLengthAwarePaginatorを使用して手動でページネーションを作成します
        $results = new LengthAwarePaginator(
            $movies['results'],
            $movies['total_results'],
            $movies['results_per_page'],
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // 検索結果をビューに返します
        return view('movies.search', ['movies' => $results]);
    }
    
    public function select(Request $request)
    {
        $movieId = $request->get('movieId');

        // TMDB APIを使用して映画詳細を取得します
        $movie = $this->getMovieDetails($movieId);

        // 映画の詳細を要素としてセッション内の配列に保存する
        $request->session()->push('selected_movies', $movie);

        // 検索結果画面にリダイレクト
        return redirect()->route('groups.create');
    }
    
    public function unselect(Request $request)
    {
        $movieKey = $request->get('movie_key');
        
        // セッション内から指定のキーのデータを削除する
        $request->session()->forget("selected_movies.$movieKey");
    
        return back();
    }

    public function index()
    {
        $apiKey = config('tmdb.api_key');
        $response = Http::get("https://api.themoviedb.org/3/movie/10000?api_key={$apiKey}");
        $movie = $response->json();
    
        return view('movies.list', compact('movie'));
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
