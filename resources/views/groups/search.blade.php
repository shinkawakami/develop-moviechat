<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <title>MovieChat - Search</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    </head>
    <body>
        <x-app-layout>
            <x-slot name="header">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    グループ検索
                </h2>
            </x-slot>
            
            <div>
                <div>
                    <form method="GET" action="{{ route('groups.searchResults') }}">
                        <label for="keyword">キーワード検索</label>
                        <input type="text" name="keyword" required maxlength="20">
                        <input type="submit" value="検索">
                    </form>
                </div>
                <div>
                    <h3>条件検索</h3>
                    <form method="GET" action="{{ route('groups.searchResults') }}">
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
                        <input type="submit" value="検索">
                    </form>
                </div>
            </div>
        </x-app-layout>
    </body>
</html>