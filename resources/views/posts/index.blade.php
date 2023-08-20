<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <title>MovieChat - IndexPosts</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
        <link href="{{ asset('css/posts/index.css') }}" rel="stylesheet">
    </head>
    <body>
        <x-app-layout>
            <section class="section">
                <div class="container">
                    <h1 class="title">掲示板</h1>
                    
                    <a href="{{ route('posts.create') }}" class="button is-primary is-pulled-right">投稿する</a>
                    
                    <form method="GET" action="{{ route('posts.search') }}" class="field has-addons">
                        <div class="control">
                            <input class="input" type="text" name="keyword" placeholder="キーワードを入力" required maxlength="50">
                        </div>
                        <div class="control">
                            <input class="button is-info" type="submit" value="検索">
                        </div>
                    </form>

                    @foreach ($posts as $post)
                        <a href="{{ route('posts.show', $post->id) }}">
                            <div class="post-card">
                                <div>
                                    <strong class="post-title">
                                        {{ $post->title }}
                                    </strong>
                                </div>
                                <div class="card-content">
                                    <div class="user-info">
                                        <span>
                                            @if(empty($post->user->image_url))
                                                <i class="fas fa-user rounded-icon"></i>
                                            @else
                                                <img src="{{ $post->user->image_url }}" alt="Profile Image" class="rounded-icon">
                                            @endif
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
                                    <div class="like-section">
                                        <span type="submit" class="has-text-danger">
                                            <i class="far fa-heart"></i> いいね
                                        </span>
                                        <span class="like-count">{{ $post->likes->count() }}</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach

                </div>
            </section>
        </x-app-layout>
    </body>
</html>
