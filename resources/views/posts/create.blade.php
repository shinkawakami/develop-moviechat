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
                <form method="POST" action="{{ route('posts.store') }}">
                    @csrf
                    <label for="group-movies">選択した映画</label>
                    <div id="selected-movie">
                        <!-- 選択した映画はここに表示されます -->
                    </div>
                    <input type="hidden" id="movie" name="movie">
                    <input type="text" name="title" placeholder="タイトル" maxlength="50" required>
                    <textarea name="content" placeholder="内容" maxlength="255" required></textarea>
                    <input type="submit" value="投稿">
                </form>
            </div>
            <div>
                <form id="movie-search-form">
                    <input type="text" id="movie-search" placeholder="映画検索">
                    <button id="movie-search-btn">検索</button>
                </form>
                <div id="movie-search-results"></div>
            </div>
        </x-app-layout>
        <script src="{{ asset('js/createPost.js') }}"></script>
    </body>
</html>
