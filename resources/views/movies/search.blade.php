<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <title>Movie</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    </head>
    <body>
        
            
            <div class='button'>
                <div class='group_make_page'>
                    <a href="/movies/make">グループ作成</a>
                </div>
                <div class='group_search_page'>
                    <a href="/movies/search/group">グループ検索</a>
                </div>
                <div class='group_showlist_page'>
                    <a href="/movies/showlist">グループ一覧</a>
                </div>
            </div>
            
            <div>
                <div>
                    <form action="/movies/search/group/result" method="GET">
                        <label for="group-name">グループ名で検索</label>
                        <input type="text" name="group_name">
                        <input type="submit" value="グループ検索">
                    </form>
                </div>
                <div>
                    <form action="/movies/search/movie" method="GET">
                        <label for="movie-search">映画で検索</label>
                        <input type="submit" value="映画検索" name="movie_search">
                    </form>
                </div>
                <div>
                    <h3>条件検索</h3>
                    <form action="/movies/search/group/result" method="GET">
                        <div>
                            <label for="group-movie-era">映画の年代</label>
                            <select name="group_movie_era_id[]" multiple>
                                <option value="">年代を選択してください</option>
                                @foreach ($eras as $era)
                                    <option value="{{ $era->id }}" selected>{{ $era->era }}</option>
                                @endforeach
                            </select> 
                        </div>
                        <div>
                            <label for="group-movie-genre">ジャンル</label>
                            <select name="group_movie_genre_id[]" multiple>
                                <option value="">ジャンルを選択してください</option>
                                @foreach ($genres as $genre)
                                    <option value="{{ $genre->id }}">{{ $genre->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="group-movie-platform">視聴方法</label>
                            <select name="group_movie_platform_id[]" multiple>
                                <option value="">視聴方法を選択してください</option>
                                @foreach ($platforms as $platform)
                                    <option value="{{ $platform->id }}">{{ $platform->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <input type="submit" value="グループ検索">
                    </form>
                </div>
            </div>
       
    </body>
</html>