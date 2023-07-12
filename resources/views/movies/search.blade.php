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
                    検索結果
                </h2>
            </x-slot>
            
            <div class="container">
            
                @foreach($movies as $movie)
                <div class="movie">
                    <h2>{{ $movie['title'] }}</h2>
                    <p>{{ $movie['overview'] }}</p>
                    <img src="https://image.tmdb.org/t/p/w500{{ $movie['poster_path'] }}" alt="Poster of {{ $movie['title'] }}" style="width: 200px; height: auto;">
            
                    <form action="{{ route('movies.select') }}" method="POST">
                        @csrf
                        <input type="hidden" name="movieId" value="{{ $movie['id'] }}">
                        <button type="submit" class="btn btn-primary">この映画を選択</button>
                    </form>
                </div>
                @endforeach
                
                {{ $movies->links() }}
            
                <a href="{{ route('groups.create') }}" class="btn btn-secondary">グループ作成に戻る</a>
            </div>
        </x-app-layout>
    </body>
</html>