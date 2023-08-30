<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Http\Requests\Movie\SearchRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;

class MovieController extends Controller
{
    private $apiKey;
    private $baseApiUrl;

    public function __construct()
    {
        $this->apiKey = config('tmdb.api_key');
        $this->baseApiUrl = 'https://api.themoviedb.org/3';
    }

    // 映画一覧画面の表示
    public function index()
    {
        $popularMoviesResponse = Http::get("{$this->baseApiUrl}/movie/popular", [
            'api_key' => $this->apiKey,
            'language' => 'ja-JP',
            'page' => 1
        ]);

        $genresResponse = Http::get("{$this->baseApiUrl}/genre/movie/list", [
            'api_key' => $this->apiKey,
            'language' => 'ja-JP'
        ]);
        
        $popularMovies = $popularMoviesResponse->json()['results'] ?? [];
        $genres = $genresResponse->json()['genres'];
        

        return view('movies.index', compact('popularMovies', 'genres'));
    }
        
    // 映画検索の処理（JSONで返す）
    public function search(Request $request)
    {
        $query = $request->input('query');
        $genres = $request->input('genres');
        $startYear = $request->input('startYear');
        $endYear = $request->input('endYear');
        $page = $request->input('page', 1);
        
        $parameters = [
            'api_key' => $this->apiKey,
            'language' => 'ja-JP',
            'query' => $query,
            'page' => $page
        ];
        
        if ($genres) {
            $parameters['with_genres'] = $genres;
        }

        if ($startYear && $endYear) {
            $parameters['primary_release_date.gte'] = "{$startYear}-01-01";
            $parameters['primary_release_date.lte'] = "{$endYear}-12-31";
        }

        if (!$query && ($genres || $startYear || $endYear)) {
            $searchResponse = Http::get("{$this->baseApiUrl}/discover/movie", $parameters);
        } else {
            $searchResponse = Http::get("{$this->baseApiUrl}/search/movie", $parameters);
        }
                
        return response()->json($searchResponse->json());
    }
       
    // 映画詳細画面の表示
    public function show($tmdb_id)
    {
        $movieData = Http::get("{$this->baseApiUrl}/movie/{$tmdb_id}", [
            'api_key' => $this->apiKey,
            'language' => 'ja-JP',
            'append_to_response' => 'videos'
        ])->json();
    
        $movie = Movie::updateOrCreateFromTMDB($movieData)->load(['groups', 'posts']);
    
        return view('movies.show', compact('movie', 'movieData'));
    }
}
