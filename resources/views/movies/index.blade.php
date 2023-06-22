<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <title>Movie</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    </head>
    <body>
        <h1>Homepage</h1>
        <div class='button'>
            <div class='group_make_page'>
                <a href="/movies/make">グループ作成</a>
            </div>
            <div class='group_search_page'>
                <a href="/movies/search">グループ検索</a>
            </div>
            <div class='group_showlist_page'>
                <a href="/movies/showlist">グループ一覧</a>
            </div>
        </div>
        <div class='posts'>
            @foreach ($pages as $page)
                <div class='post'>
                    <h2 class='title'>{{ $page->title }}</h2>
                    <p class='body'>{{ $page->body }}</p>
                </div>
            @endforeach
        </div>
    </body>
</html>