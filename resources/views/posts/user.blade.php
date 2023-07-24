<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <title>MovieChat - My Posts</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
        <link href="{{ asset('css/posts/user.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    </head>
    <body>
        <x-app-layout>
           <section class="section">
                <div class="container">
                    <h1 class="title">自分の投稿</h1>
                    
                    <div class="button is-primary is-pulled-right">
                        <a href="{{ route('posts.create') }}">投稿する</a>
                    </div>
                    
                    <div class="is-clearfix"></div>

                    @foreach ($posts as $post)
                        <a href="{{ route('posts.show', $post->id) }}">
                            <div class="post-card">
                                <div>
                                    <strong class="post-title">
                                        {{ $post->title }}
                                    </strong>
                                </div>
                                <div class="card-content">
                                    <div class="movie-info">
                                        <span class="icon"><i class="fa fa-film"></i></span>
                                        <span class="movie-title">{{ $post->movie->title }}</span>
                                    </div>
                                    <p class="post-text">{{ $post->content }}</p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>
        </x-app-layout>
    </body>
</html>
