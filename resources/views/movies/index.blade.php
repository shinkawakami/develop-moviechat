<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <title>MovieChat - Movie List</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    </head>
    <body>
        <x-app-layout>
            <x-slot name="header">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    映画一覧
                </h2>
            </x-slot>

            <div>
                <form id="movie-search-form">
                    <input type="text" id="movie-search" placeholder="キーワード検索">
                    <button id="movie-search-btn">検索</button>
                </form>
                <div id="movie-search-results"></div>
            </div>

            <div id="popular-movies">
                @foreach($popular_movies as $movie)
                    <div class="movie">
                        <h3><a href="{{ route('movies.show', ['movie' => $movie['id']]) }}">{{ $movie['title'] }}</a></h3>
                        <img src="https://image.tmdb.org/t/p/w500{{ $movie['poster_path'] }}" alt="{{ $movie['title'] }}">
                        <p>{{ $movie['overview'] }}</p>
                    </div>
                @endforeach
            </div>

        </x-app-layout>

        <script src="{{ asset('js/indexMovie.js') }}"></script>
    </body>
</html>
