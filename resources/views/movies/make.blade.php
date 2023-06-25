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
    
            <form action="/movies" method="post">
                @csrf
        
                <div>
                    <label for="name">Group Name</label>
                    <input type="text" name="group[name]" id='name'>
                </div>
        
                <div>
                    <label for="capacity">Capacity</label>
                    <input type="number" name="group[capacity]">
                </div>
        
                <div>
                    <label for="movie">Movie</label>
                    <input type="text" name="group[movie_id]">
                </div>
                
                <input type="hidden" name="creator" value="{{ Auth::user()->id }}">
        
                <button type="submit">Create</button>
            </form>
        </x-app-layout>
    </body>
</html>