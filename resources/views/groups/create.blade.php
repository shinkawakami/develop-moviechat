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
        
            <form action="{{ route('groups.store') }}" method="post">
                @csrf
            
                <div>
                    <label for="group_name">Group Name:</label>
                    <input id="group_name" name="group_name" type="text" maxlength="50" required>
                </div>
                
                <div>
                    <label for="group_capacity">定員</label>
                    <input type="number" name="group_capacity" min="2" max="10" required>
                </div>
            
                <div>
                    <label for="genres">Genres:</label>
                    <select id="genres" name="genres[]" multiple>
                        @foreach ($genres as $genre)
                            <option value="{{ $genre->id }}">{{ $genre->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label for="eras">Eras:</label>
                    <select id="eras" name="eras[]" multiple>
                        @foreach ($eras as $era)
                            <option value="{{ $era->id }}">{{ $era->era }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label for="platforms">Platforms:</label>
                    <select id="platforms" name="platforms[]" multiple>
                        @foreach ($platforms as $platform)
                            <option value="{{ $platform->id }}">{{ $platform->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                
        
                
        
                <div id="selected-movies" class="mt-3">
                    <!-- 選択した映画はここに表示されます -->
                </div>
                
                <input type="hidden" id="movies" name="movies">
        
                <div class="form-group mt-3">
                    <button type="submit" class="btn btn-primary">作成</button>
                </div>
            </form>
            
                
            <div class="form-group">
                <label for="movie-search">好きな映画</label>
                <input type="text" id="movie-search" class="form-control">
                <button id="movie-search-btn" class="btn btn-primary">検索</button>
                
            </div>
            
            <div id="movie-search-results" class="mt-3">
                    <!-- 映画の検索結果はここに表示されます -->
                </div>
       
            <script src="{{ asset('js/createGroup.js') }}"></script>
        </x-app-layout>    
    </body>
</html>