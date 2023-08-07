<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Http\Requests\Movie\SearchRequest;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;

class MovieController extends Controller
{
    public function index()
    {
        $apiKey = config('tmdb.api_key');
        $response = Http::get("https://api.themoviedb.org/3/movie/popular?api_key={$apiKey}&language=ja-JP&page=1");
        $popularMovies = $response->json()['results'] ?? [];

        return view('movies.index', compact('popularMovies'));
    }
        
    public function search(SearchRequest $request)
    {
        $validatedData = $request->validated();
        
        $query = $validatedData['query'];
        $page = $validatedData['page'] ?? 1;
    
        $client = new Client();
        $response = $client->get('https://api.themoviedb.org/3/search/movie', [
            'query' => [
                'api_key' => config('tmdb.api_key'),
                'query' => $query,
                'page' => $page,
                'language' => 'ja-JP'
            ]
        ]);
    
        $movieData = json_decode($response->getBody(), true);
    
        return response()->json($movieData);
    }
        
    public function show($tmdb_id)
    {
        $apiKey = config('tmdb.api_key');
        $response = Http::get("https://api.themoviedb.org/3/movie/{$tmdb_id}?api_key={$apiKey}&language=ja-JP");
        $movieData = $response->json();
    
        $movie = Movie::updateOrCreateFromTMDB($movieData)->load(['groups', 'posts']);
        
        return view('movies.show', compact('movie'));
    }
}
