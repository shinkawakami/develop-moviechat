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
            <h1>Create Group</h1>
    
            <form action="/movies/make" method="post">
                @csrf
                <div>
                    <label for="group-name">グループ名</label>
                    <input type="text" name="group_name">
                </div>
                <div>
                    <label for="group-capacity">定員</label>
                    <input type="number" name="group_capacity">
                </div>
                <div>
                    <label for="group-movie-title">映画タイトル</label>
                    <select name="group_movie_title_id[]" multiple>
                        <option value="">映画タイトルを選択してください</option>
                        @foreach ($movies as $movie)
                            <option value="{{ $movie->id }}">{{ $movie->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                  <a href="/movies/add">映画を追加</a>  
                </div>
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
        
                <button type="submit">Create</button>
            </form>
                
        
    </body>
</html>