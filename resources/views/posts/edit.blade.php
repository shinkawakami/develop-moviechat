<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <title>MovieChat - Edit Post</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    </head>
    <body>
        <x-app-layout>
            <x-slot name="header">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    投稿編集
                </h2>
            </x-slot>
        
            <!-- 投稿編集フォーム -->
            <div>
                <form method="POST" action="{{ route('posts.update', $post) }}">
                    @csrf
                    @method('PUT')
                    <label for="group-movies">選択した映画</label>
                    <div id="selected-movie">
                        <!-- 選択した映画はここに表示されます -->
                        @if($post->movie)
                            <p>{{ $post->movie->title }}</p>
                        @endif
                        <button id="remove-movie-btn">取り消し</button>
                    </div>
                    <input type="hidden" id="movie" name="movie" value="{{ $post->movie->tmdb_id }}">
                    <input type="text" name="title" placeholder="タイトル" value="{{ $post->title }}" maxlength="50" required>
                    <textarea name="content" placeholder="内容" maxlength="255" required>{{ $post->content }}</textarea>
                    <input type="submit" value="更新">
                </form>
            </div>
            <div>
                <form id="movie-search-form">
                    <input type="text" id="movie-search" placeholder="映画検索">
                    <button id="movie-search-btn">検索</button>
                </form>
                <div id="movie-search-results"></div>
            </div>
            <script src="{{ asset('js/editPost.js') }}"></script>
        </x-app-layout>
    </body>
</html>
