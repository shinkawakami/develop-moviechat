<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <title>MovieChat - movieSearch</title>
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
            
            <form method="GET" action="{{ route('movies.search') }}">
                <input type="text" name="movie_title" placeholder="映画タイトルを記入してください">
                <input type="submit" value='検索'>
            </form>
            
            @if(isset($popular_movies))
                @foreach($popular_movies as $movie)
                    <div>
                        <h2>{{ $movie['title'] }}</h2>
                        <p>{{ $movie['overview'] }}</p>
                        <img src="https://image.tmdb.org/t/p/w500{{ $movie['poster_path'] }}" alt="Poster of {{ $movie['title'] }}" style="width: 200px; height: auto;">
                        
                        <form action="{{ route('movies.select') }}" method="POST">
                            @csrf
                            <input type="hidden" name="movieId" value="{{ $movie['id'] }}">
                            <input type="hidden" name="actionType" value="group">
                            <button type="submit" class="btn btn-primary">この映画でグループを作成</button>
                        </form>
                        
                        <form action="{{ route('movies.select') }}" method="POST">
                            @csrf
                            <input type="hidden" name="movieId" value="{{ $movie['id'] }}">
                            <input type="hidden" name="actionType" value="post">
                            <button type="submit" class="btn btn-primary">この映画で投稿を作成</button>
                        </form>
                    </div>
                @endforeach
            @endif
                
            @if(isset($movies))
                @if($movies->count() > 0)
                    @foreach($movies as $movie)
                    <div class="movie">
                        <h2>{{ $movie['title'] }}</h2>
                        <p>{{ $movie['overview'] }}</p>
                        <img src="https://image.tmdb.org/t/p/w500{{ $movie['poster_path'] }}" alt="Poster of {{ $movie['title'] }}" style="width: 200px; height: auto;">
                
                        <form action="{{ route('movies.select') }}" method="POST">
                            @csrf
                            <input type="hidden" name="movieId" value="{{ $movie['id'] }}">
                            <input type="hidden" name="actionType" value="group">
                            <button type="submit" class="btn btn-primary">この映画でグループを作成</button>
                        </form>
                        
                        <form action="{{ route('movies.select') }}" method="POST">
                            @csrf
                            <input type="hidden" name="movieId" value="{{ $movie['id'] }}">
                            <input type="hidden" name="actionType" value="post">
                            <button type="submit" class="btn btn-primary">この映画で投稿を作成</button>
                        </form>
                    </div>
                    @endforeach
                    
                    {{ $movies->links() }}
                
                @else
                    <p>該当する映画は見つかりませんでした。</p>
                @endif
            @endif
        </x-app-layout>
    </body>
</html>