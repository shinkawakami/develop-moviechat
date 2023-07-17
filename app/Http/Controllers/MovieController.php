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
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
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
        
    public function search(Request $request)
    {
        $query = $request->get('query');
        $page = $request->get('page', 1);
    
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
        // Fetch movie details
        $apiKey = config('tmdb.api_key');
        $response = Http::get("https://api.themoviedb.org/3/movie/{$tmdb_id}?api_key={$apiKey}&language=ja-JP");
        $movieData = $response->json();
        
        return response()->json($movieData);
    
        // Get or create the movie in local database
        /*$movie = Movie::firstOrCreate(
            ['tmdb_id' => $movieData['id']],
            ['title' => $movieData['title']]
        );
        
        
        
    
    
        $posts = $movie->posts;  // Get all posts related to this movie
        $groups = $movie->groups;  // Get all groups related to this movie
        
        return view('movies.show', [
            'movie' => $movieData,
            'posts' => $posts,
            'groups' => $groups,
        ]);*/
    }
}
