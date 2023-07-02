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
                    <form action="/movies/search/movie/result" method="GET">
                        <label for="movie-title">映画のタイトル</label>
                        <input type="text" name="movie_title">
                        <input type="submit" value="映画検索">
                    </form>
                </div>
                <div>
                    <form action="/movies/search/movie/result" method="GET">
                        <label for="movie-title_id">映画のタイトル</label>
                            <select name="movie_title_id">
                                <option value="">映画を選択してください</option>
                                @foreach ($movies as $movie)
                                    <option value="{{ $movie->id }}" selected>{{ $movie->title }}</option>
                                @endforeach
                            </select>
                        <input type="submit" value="映画検索">
                    </form>
                </div>
                <div>
                    <h3>条件検索</h3>
                    <form action="/movies/search/movie/result" method="GET">
                        <div>
                            <label for="movie-era">映画の年代</label>
                            <select name="movie_era_id">
                                <option value="">年代を選択してください</option>
                                @foreach ($eras as $era)
                                    <option value="{{ $era->id }}" selected>{{ $era->era }}</option>
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
       
    </body>
</html>