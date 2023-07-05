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
                    Search Movie
                </h2>
            </x-slot>
            
            <div>
                <div>
                    <form action="/moviechat/movie/result" method="GET">
                        <label for="movie-title">映画のタイトル</label>
                        <input type="text" name="movie_title" required maxlength="20">
                        <input type="submit" value="映画検索">
                    </form>
                </div>
                <div>
                    <form action="/moviechat/movie/result" method="GET">
                        <label for="movie-title_id">映画のタイトル</label>
                            <select name="movie_title_id" required>
                                <option value="">映画を選択してください</option>
                                @foreach ($movies as $movie)
                                    <option value="{{ $movie->id }}">{{ $movie->title }}</option>
                                @endforeach
                            </select>
                        <input type="submit" value="映画検索">
                    </form>
                </div>
                <div>
                    <h3>条件検索</h3>
                    <form action="/moviechat/movie/result" method="GET">
                        <div>
                            <label for="movie-era">映画の年代</label>
                            <select name="movie_era_id">
                                <option value="">年代を選択してください</option>
                                @foreach ($eras as $era)
                                    <option value="{{ $era->id }}">{{ $era->era }}</option>
                                @endforeach
                            </select> 
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
                                <option value="">プラットフォームを選択してください</option>
                                @foreach ($platforms as $platform)
                                    <option value="{{ $platform->id }}">{{ $platform->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <input type="submit" value="映画検索">
                    </form>
                </div>
            </div>
        </x-app-layout>
    </body>
</html>