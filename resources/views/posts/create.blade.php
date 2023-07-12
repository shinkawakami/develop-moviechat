<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <title>MovieChat - Create Post</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    </head>
    <body>
        <x-app-layout>
            <x-slot name="header">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    投稿作成
                </h2>
            </x-slot>
            <!-- 投稿作成フォーム -->
            <div>
                <a href="{{ route('movies.index') }}">映画検索</a>
            </div>
            <div>
                <label for="group-movies">選択した映画</label>
                
            <div class="selected-movies">
                <p>{{ $movie['title'] ?? 'No movie selected' }}</p>
                <form action="{{ route('movies.unselect') }}" method="POST">
                    @csrf
                    <input type="hidden" name="actionType" value="post">
                    <button type="submit">削除</button>
                </form>
            </div>
            
            </div>
            <div>
                <form method="POST" action="{{ route('posts.store') }}">
                    @csrf
                    <input type="text" name="title" placeholder="タイトル">
                    <textarea name="content" placeholder="内容"></textarea>
                    <input type="submit" value="投稿">
                </form>
            </div>
        </x-app-layout>
    </body>
</html>
