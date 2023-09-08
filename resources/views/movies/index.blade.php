<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <title>MovieChat - IndexMovies</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
    <link href="{{ asset('css/movies/index.css') }}" rel="stylesheet">
</head>

<body>
    <x-app-layout>
        <section class="section">
            <div class="container">
                
                <div class="is-flex is-justify-content-space-between">
                    <h1 class="title">映画一覧</h1>
                    <button id="show-search-form" class="button is-info">検索する</button>
                </div>
                
                <div id="search-form-container" style="display: none;" class="block">
                    <div class="box">
                        <h2 class="subtitle">映画検索</h2>
                        
                        <div class="box">
                            <label class="label">キーワード</label>
                            <div class="field has-addons">
                                <div class="control">
                                    <input type="text" id="movie-search" class="input" placeholder="キーワードを入力">
                                </div>
                                <div class="control">
                                    <button id="keyword-search-btn" class="button is-info">検索</button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="box">
                            <div class="block">
                                <label class="label">ジャンル</label>
                                @foreach($genres as $genre)
                                    <div class="genre-button" data-genre-id="{{ $genre['id'] }}">
                                        {{ $genre['name'] }}
                                    </div>
                                @endforeach
                            </div>
                            
                            <div class="block">
                                <label class="label">年代</label>
                                <select name="startYear" id="start-year">
                                    <option value="" selected>開始年</option>
                                    @for($year = 1920; $year <= now()->year; $year++)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endfor
                                </select>
                                から
                                <select name="endYear" id="end-year">
                                    <option value="" selected>終了年</option>
                                    @for($year = 1920; $year <= now()->year; $year++)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endfor
                                </select>
                                まで
                            </div>
                            
                            <div class="block">
                                <button id="filter-search-btn" class="button is-info">検索</button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="box block">
                    <div id="search-results"></div>
                    
                    <div id="pagination-container" style="display: none;">
                        <button id="prev-page" class="button is-info">前のページ</button>
                        
                        <button id="next-page" class="button is-info">次のページ</button>
                    </div>
                    
                    <div id="popular-movies">
                        @foreach($popularMovies as $movie)
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
