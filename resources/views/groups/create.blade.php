<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <title>MovieChat - create</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    </head>
    <body>
        <x-app-layout>
            <x-slot name="header">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    グループ作成
                </h2>
            </x-slot>
    
            <div>
                <a href="{{ route('movies.index') }}">映画検索</a>
            </div>
            
            <div>
                <label for="group-movies">選択した映画</label>
                @foreach($selectedMovies as $key => $movie)
                <div class="selected-movies">
                    <p>{{ $movie['title'] }}</p>
                    <form action="{{ route('movies.unselect') }}" method="POST">
                        @csrf
                        <input type="hidden" name="movie_key" value="{{ $key }}">
                        <input type="hidden" name="actionType" value="group">
                        <button type="submit">削除</button>
                    </form>
                </div>
                @endforeach
            </div>
            
            <form action="/moviechat/groups/create" method="POST">
                @csrf
                <div>
                    <label for="group-name">グループ名</label>
                    <input type="text" name="group[name]" maxlength="20">
                </div>
                <div>
                    <label for="group-capacity">定員</label>
                    <input type="number" name="group[capacity]" min="2" max="10">
                </div>
                
                <div>
                    <label for="group-movie-era">映画の年代</label>
                    <select name="group[movie_era_ids][]" multiple>
                        <option value="">年代を選択してください</option>
                        @foreach ($eras as $era)
                            <option value="{{ $era->id }}">{{ $era->era }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="group-movie-genre">ジャンル</label>
                    <select name="group[movie_genre_ids][]" multiple>
                        <option value="">ジャンルを選択してください</option>
                        @foreach ($genres as $genre)
                            <option value="{{ $genre->id }}">{{ $genre->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="group-movie-platform">視聴方法</label>
                    <select name="group[movie_platform_ids][]" multiple>
                        <option value="">視聴方法を選択してください</option>
                        @foreach ($platforms as $platform)
                            <option value="{{ $platform->id }}">{{ $platform->name }}</option>
                        @endforeach
                    </select>
                </div>
            
                <button type="submit">Create</button>
            </form>

        </x-app-layout>        
    </body>
</html>