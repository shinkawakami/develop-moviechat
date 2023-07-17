<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with(['user', 'movie'])->get();
        return view('posts.index', compact('posts'));
    }

    public function search(Request $request)
    {
        $search = $request->get('search');
        $posts = Post::whereHas('movie', function ($query) use ($search) {
            $query->where('title', 'like', '%' . $search . '%');
        })->orWhere('title', 'like', '%' . $search . '%')->with(['user', 'movie'])->get();

        return view('posts.index', compact('posts'));
    }

    public function create(Request $request)
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'movie' => 'required|integer'
        ]);
    
        // Create a new post
        $post = new Post;
        $post->user_id = Auth::id(); // Get the currently authenticated user's ID
        $post->title = $request->title;
        $post->content = $request->content;
        
        $movieId = $request->movie;
        $apiKey = config('tmdb.api_key');
        
        if ($movieId !== '') {
            $response = Http::get("https://api.themoviedb.org/3/movie/{$movieId}?api_key={$apiKey}&language=ja-JP");
            $movieData = $response->json();

            $movie = Movie::firstOrCreate(
                ['tmdb_id' => $movieData['id']],
                ['title' => $movieData['title']]
            );

            $post->movie()->associate($movie);
        }
        
        $post->save();
        
        return redirect()->route('posts.index');
    }

    public function user()
    {
        $user = auth()->user();
        $posts = Post::where('user_id', $user->id)->with('movie')->get();
        return view('posts.user', compact('posts'));
    }

    public function show(Post $post)
    {
        $comments = $post->comments()->with('user')->get();
        return view('posts.show', compact('post', 'comments'));
    }

    public function edit(Post $post)
    {
        return view('posts.edit', compact('post'));
    }
    
    public function update(Request $request, Post $post)
    {
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'movie' => 'required|integer'
        ]);
    
        $post->title = $request->title;
        $post->content = $request->content;
    
        $movieId = $request->movie;
        $apiKey = config('tmdb.api_key');
    
        if ($movieId !== '') {
            $response = Http::get("https://api.themoviedb.org/3/movie/{$movieId}?api_key={$apiKey}&language=ja-JP");
            $movieData = $response->json();
    
            $movie = Movie::firstOrCreate(
                ['tmdb_id' => $movieData['id']],
                ['title' => $movieData['title']]
            );
    
            $post->movie()->associate($movie);
        }
    
        $post->save();
    
        return redirect()->route('posts.show', $post);
    }


    public function destroy(Post $post)
    {
        $post->delete();
        return redirect()->route('posts.user');
    }

    public function comment(Request $request, Post $post)
    {
        $request->validate([
            'comment' => 'required',
        ]);

        $comment = new Comment;
        $comment->user_id = auth()->id();
        $comment->post_id = $post->id;
        $comment->content = $request->comment;
        $comment->save();

        return back();
    }
}
