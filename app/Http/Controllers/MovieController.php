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
    
    public function index()
    {
        $apiKey = config('tmdb.api_key');
        $response = Http::get("https://api.themoviedb.org/3/movie/popular?api_key={$apiKey}&language=ja-JP&page=1");
        $movies = $response->json()['results'] ?? [];

        return view('movies.index', ['popular_movies' => $movies]);
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
        return view('movies.index', ['movies' => $results]);
    }
    
    public function select(Request $request)
    {
        $movieId = $request->get('movieId');
        $actionType = $request->get('actionType');

        // TMDB APIを使用して映画詳細を取得します
        $movie = $this->getMovieDetails($movieId);
        
        switch($actionType) {
            case 'group':
                // 映画の詳細を要素としてセッション内の配列に保存する
                $request->session()->push('selected_movies_for_group', $movie);
                return redirect()->route('groups.create');
            case 'post':
                $request->session()->put('selected_movie_for_post', $movie);
                return redirect()->route('posts.create');
            default:
                // 必要に応じてエラーハンドリングを行う
                return redirect()->back();
        }
    }
    
    public function unselect(Request $request)
    {
        $movieKey = $request->get('movie_key');
        $actionType = $request->get('actionType');
        
        switch($actionType) {
            case 'group':
                // セッション内から指定のキーのデータを削除する
                $request->session()->forget("selected_movies_for_group.$movieKey");
            case 'post':
                $request->session()->forget("selected_movie_for_post");
            default:
                // 必要に応じてエラーハンドリングを行う
                return redirect()->back();
        }
        
        return back();
    }
}
