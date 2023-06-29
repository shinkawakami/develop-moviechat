<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <title>Movie</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    </head>
    <body>
        <x-app-layout>
            <x-slot name="header">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Make Group
                </h2>
            </x-slot>
        
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
            <h1>Create Group</h1>
    
            <form action="/movies/make" method="post">
                @csrf
        
                <div>
                    <label for="group-name">Group Name</label>
                    <input type="text" name="group_name">
                </div>
        
                <div>
                    <label for="group-capacity">Capacity</label>
                    <input type="number" name="group_capacity">
                </div>
        
                <div>
                    <label for="movie-title">Movie</label>
                    <input type="text" name="movie_title">
                </div>
                
                <div>
                    <label for="movie-genre">Genre</label>
                    <input type="text" name="movie_genre">
                </div>
                
                <div>
                    <label for="movie-subscription">Subscription</label>
                    <input type="text" name="movie_subscription">
                </div>
                
                <div>
                    <label for="movie-released-at">Released_at</label>
                    <input type="number" name="movie_released_at">
                </div>
                
                <input type="hidden" name="group_created_id" value="{{ Auth::user()->id }}">
        
                <button type="submit">Create</button>
            </form>
                
        </x-app-layout>
    </body>
</html>