<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <title>MovieChat - IndexMovie</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
    <link href="{{ asset('css/movies/index.css') }}" rel="stylesheet">
</head>

<body>
    <x-app-layout>
        <section class="section">
            <div class="container">
                <h1 class="title">映画一覧</h1>
                
                <div class="box">
                    <h2 class="subtitle">映画検索</h2>
                    <div class="field has-addons">
                        <div class="control is-expanded">
                            <input type="text" id="movie-search" class="input" placeholder="キーワードを入力">
                        </div>
                        <div class="control">
                            <button id="search-btn" class="button is-info">検索</button>
                        </div>
                    </div>
                    
                    <div id="search-results">
                    <!-- 映画の検索結果はここに表示されます -->
                    </div>
                    <div id="popular-movies">
                        @foreach($popular_movies as $movie)
                            <div class="movie-container">
                                <a href="{{ route('movies.show', ['movie' => $movie['id']]) }}">{{ $movie['title'] }}</a>
                                <img src="https://image.tmdb.org/t/p/w500{{ $movie['poster_path'] }}">
                                <p>{{ $movie['overview'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
        <script src="{{ asset('js/indexMovie.js') }}"></script>
    </x-app-layout>
</body>

</html>
