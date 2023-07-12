<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use App\Models\Movie;
use Illuminate\Http\Request;

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
        })->orWhere('name', 'like', '%' . $search . '%')->with(['user', 'movie'])->get();

        return view('posts.index', compact('posts'));
    }

    public function create(Request $request)
    {
        // セッションから選択された映画を取得
        $selectedMovie = $request->session()->get('selected_movie_for_post', []);
        return view('posts.create', ['movie' => $selectedMovie]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
        ]);
        
        $post = new Post;
        
        // セッションに選択していた映画情報の配列を取得
        $movie = $request->session()->get('selected_movie_for_post');

        // 選択した映画がmoviesテーブルのレコードに存在しなかったらレコード作成
        
        $movieTitle = $movie['title'];
        $movieId = $movie['id'];
        
        $existingMovie = Movie::where('tmdb_id', $movieId)->first();
        
        if (!$existingMovie) {
            $movie = new Movie();
            $movie->title = $movieTitle;
            $movie->tmdb_id = $movieId;
            $movie->save();
            $post->movie_id = $movie->id;
        }
        else {
            $post->movie_id = $existingMovie->id;
        }
        
        // セッションデータを消去
        $request->session()->forget('selected_movie_for_post');

        $post->user_id = auth()->id();
        $post->title = $request->title;
        $post->content = $request->content;
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
            'movie_id' => 'required|exists:movies,id',
            'content' => 'required',
        ]);

        $post->title = $request->title;
        $post->movie_id = $request->movie_id;
        $post->content = $request->content;
        $post->save();

        return redirect()->route('post.show', $post);
    }

    public function destroy(Post $post)
    {
        $post->delete();
        return redirect()->route('post.index');
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
