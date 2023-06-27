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
                    <label for="name">Group Name</label>
                    <input type="text" name="group[name]" id='name'>
                </div>
        
                <div>
                    <label for="capacity">Capacity</label>
                    <input type="number" name="group[capacity]">
                </div>
        
                <div>
                    <label for="movie">Movie</label>
                    <input type="text" name="movie[title]">
                </div>
                
                <div>
                    <label for="genre">Genre</label>
                    <input type="text" name="genre[name]">
                </div>
                
                <div>
                    <label for="movie">Subscription</label>
                    <input type="text" name="subscription[name]">
                </div>
                
                <div>
                    <label for="released_at">Released_at</label>
                    <input type="number" name="movie[released_at]">
                </div>
                
                <input type="hidden" name="group[created_id]" value="{{ Auth::user()->id }}">
        
                <button type="submit">Create</button>
            </form>
                
        </x-app-layout>
    </body>
</html>