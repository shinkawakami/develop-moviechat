<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use App\Models\Movie;
use App\Http\Requests\Post\SearchRequest;
use App\Http\Requests\Post\CreateRequest;
use App\Http\Requests\Post\EditRequest;
use App\Http\Requests\Post\CommentRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
 
class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with(['user', 'movie'])->get();
        return view('posts.index', compact('posts'));
    }

    public function search(SearchRequest $request)
    {
        $validatedData = $request->validated();
        
        $keyword = $request->get('keyword');
        $posts = Post::searchByKeyword($keyword);

        return view('posts.index', compact('posts'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(CreateRequest $request)
    {
        $validatedData = $request->validated();
    
        $post = new Post;
        $post->user_id = Auth::id();
        $post->title = $validatedData['title'];
        $post->content = $validatedData['content'];
        
        $movieId = $validatedData['movie'];
        $apiKey = config('tmdb.api_key');
        
        $response = Http::get("https://api.themoviedb.org/3/movie/{$movieId}?api_key={$apiKey}&language=ja-JP");
        $movieData = $response->json();

        $movie = Movie::updateOrCreateFromTMDB($movieData);

        $post->movie()->associate($movie);
        
        $post->save();
        
        return redirect()->route('posts.index');
    }

    public function user()
    {
        $user = Auth::user();
        $posts = $user->posts()->with('movie')->get();
        return view('posts.user', compact('posts'));
    }

    public function show($postId)
    {
        $post = Post::with(['user', 'comments.user'])->findOrFail($postId);
        return view('posts.show', compact('post'));
    }

    public function edit($postId)
    {
        $post = Post::findOrFail($postId);
        return view('posts.edit', compact('post'));
    }
    
    public function update(EditRequest $request, $postId)
    {
        $validatedData = $request->validated();
    
        $post = Post::findOrFail($postId);
        $post->title = $validatedData['title'];
        $post->content = $validatedData['content'];
    
        $movieId = $validatedData['movie'];
        $apiKey = config('tmdb.api_key');
    
        $response = Http::get("https://api.themoviedb.org/3/movie/{$movieId}?api_key={$apiKey}&language=ja-JP");
        $movieData = $response->json();

        $movie = Movie::updateOrCreateFromTMDB($movieData);

        $post->movie()->associate($movie);
    
        $post->save();
    
        return redirect()->route('posts.show', $post);
    }


    public function destroy($postId)
    {
        $post = Post::findOrFail($postId);
        $post->delete();
        return redirect()->route('posts.user');
    }

    public function comment(CommentRequest $request, $postId)
    {
        $validatedData = $request->validated();
        
        $post = Post::findOrFail($postId);

        $comment = new Comment;
        $comment->user_id = Auth::id();
        $comment->post_id = $post->id;
        $comment->content = $validatedData['comment'];
        $comment->save();

        return back();
    }
}
