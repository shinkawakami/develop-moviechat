<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
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
        })->with(['user', 'movie'])->get();

        return view('posts.index', compact('posts'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $movie_title = $request->movie_title;
        //
        
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
        ]);

        $post = new Post;
        $post->user_id = auth()->id();
        $post->title = $request->title;
        //$post->movie_id = $request->movie_id;
        $post->content = $request->content;
        $post->save();

        return redirect()->route('post.index');
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
        $comment->comment = $request->comment;
        $comment->save();

        return back();
    }
}
