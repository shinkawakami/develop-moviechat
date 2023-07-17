<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <title>MovieChat - Posts</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    </head>
    <body>
        <x-app-layout>
            <x-slot name="header">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    掲示板
                </h2>
            </x-slot>
            <!-- 検索フォーム -->
            <div>
                <form method="GET" action="{{ route('posts.search') }}">
                    <input type="text" name="keyword" placeholder="キーワード検索" required maxlength="50">
                    <input type="submit" value="検索">
                </form>
            </div>
            <div>
                <a href="{{ route('posts.create') }}">投稿する</a>  
            </div>
            <!-- 投稿一覧 -->
            <div>
                @foreach ($posts as $post)
                    <p><a href="{{ route('posts.show', $post->id) }}">{{ $post->title }}</a></p>
                    <p>{{ $post->user->name }}</p>
                    @if($post->movie)
                        <p>{{ $post->movie->title }}</p>
                    @endif
                    <p>{{ $post->content }}</p>
                @endforeach
            </div>
        </x-app-layout>
    </body>
</html>
