<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <title>MovieChat</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    </head>
    <body>
        <x-app-layout>
            <x-slot name="header">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Add Movie
                </h2>
            </x-slot>
    
            <form action="/moviechat/movie/create" method="post">
                @csrf
                <div>
                    <label for="movie-title">映画タイトル</label>
                    <input type="text" name="movie_title" required maxlength="20">
                </div>
                <div>
                    <label for="movie-genre">ジャンル</label>
                    <select name="movie_genre_id[]" multiple>
                        <option value="">ジャンルを選択してください</option>
                        @foreach ($genres as $genre)
                            <option value="{{ $genre->id }}">{{ $genre->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="movie-platform">視聴方法</label>
                    <select name="movie_platform_id[]" multiple>
                        <option value="">視聴方法を選択してください</option>
                        @foreach ($platforms as $platform)
                            <option value="{{ $platform->id }}">{{ $platform->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="movie-year">公開年度</label>
                    <input type="number" name="movie_year" min="1900" max="{{ date('Y') }}">
                </div>
    
                <button type="submit">Create</button>
            </form>
        </x-app-layout>
    </body>
</html>