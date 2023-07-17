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
        $movies = $response->json()['results'] ?? [];

        return view('movies.index', ['popular_movies' => $movies]);
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
    
    public function details($tmdb_id)
    {
        // Fetch movie details
        $apiKey = config('tmdb.api_key');
        $response = Http::get("https://api.themoviedb.org/3/movie/{$tmdb_id}?api_key={$apiKey}&language=ja-JP");
        $movieData = $response->json();
    
        return response()->json($movieData);
    }
        
    public function show($tmdb_id)
    {
        // Fetch movie details
        $apiKey = config('tmdb.api_key');
        $response = Http::get("https://api.themoviedb.org/3/movie/{$tmdb_id}?api_key={$apiKey}&language=ja-JP");
        $movieData = $response->json();
    
        // Get or create the movie in local database
        $movie = Movie::firstOrCreate(
            ['tmdb_id' => $movieData['id']],
            ['title' => $movieData['title']]
        );
    
        $posts = $movie->posts;  // Get all posts related to this movie
        $groups = $movie->groups;  // Get all groups related to this movie
        
        return view('movies.show', [
            'movie' => $movieData,
            'posts' => $posts,
            'groups' => $groups,
        ]);
    }
}
