<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <title>MovieChat - DetailPost</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
        <link href="{{ asset('css/posts/show.css') }}" rel="stylesheet"> 
    </head>
    <body>
        <x-app-layout>
            <section class="section">
                <div class="container">
                    <div class="header-flex">
                        <h1 class="title">投稿詳細</h1>
                        <a href="{{ route('posts.index') }}">戻る</a>
                    </div>
                    
                    <div class="post-card box">
                        <div class="post-title"><strong>{{ $post->title }}</strong></div>
                        <div class="user-info">
                            <span>
                                <a href="{{ route('profile.show', $post->user) }}">
                                    @if(empty($post->user->image_url))
                                        <i class="fas fa-user rounded-icon"></i>
                                    @else
                                        <img src="{{ $post->user->image_url }}" alt="Profile Image" class="rounded-icon">
                                    @endif
                                </a>
                            </span>
                            <span class="username">{{ $post->user->name }}</span>
                        </div>
                        <div class="movie-info">
                            <span class="icon"><i class="fa fa-film"></i></span>
                            <span class="movie-title">{{ $post->movie->title }}</span>
                        </div>
                        <div>
                            @for ($i = 1; $i <=5; $i++) 
                                @if($i <= $post->rating)
                                    <span>★<span>
                                @else
                                    <span>☆<span>
                                @endif
                            @endfor
                        </div>
                        <p class="post-text">{{ $post->content }}</p>

                        @if (Auth::user()->id == $post->user_id)
                            <div class="control-buttons">
                                <a href="{{ route('posts.edit', $post->id) }}" class="button is-warning">編集</a>
                                <form action="{{ route('posts.destroy', $post->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="button is-dark" type="submit">投稿削除</button>
                                </form>
                            </div>
                        @endif
                    

                        <form method="POST" action="{{ route('posts.comment', $post->id) }}" class="comment-form">
                            @csrf
                            <textarea class="textarea" name="comment" placeholder="コメントを入力" maxlength="255" required></textarea>
                            <input class="button is-primary" type="submit" value="コメント投稿">
                        </form>
                        
                        @foreach ($post->comments as $comment)
                            <div class="comment-card">
                                <div class="user-info">
                                    <span>
                                        <a href="{{ route('profile.show', $comment->user) }}">
                                            @if(empty($comment->user->image_url))
                                                <i class="fas fa-user rounded-icon"></i>
                                            @else
                                                <img src="{{ $comment->user->image_url }}" alt="Profile Image" class="rounded-icon">
                                            @endif
                                        </a>
                                    </span>
                                    <span class="username">{{ $comment->user->name }}</span>
                                </div>
                                <p>{{ $comment->content }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        </x-app-layout>
    </body>
</html>
