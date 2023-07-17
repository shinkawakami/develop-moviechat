<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <title>MovieChat - edit</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    </head>
    <body>
        <x-app-layout>
            <x-slot name="header">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    グループ編集
                </h2>
            </x-slot>
    
            <form action="{{ route('groups.update', $group->id) }}" method="post">
                @csrf
                @method('PUT')
            
                <div>
                    <label for="group_name">Group Name:</label>
                    <input id="group_name" name="group_name" type="text" value="{{ $group->name }}" required>
                </div>
                
                <div>
                    <label for="group_capacity">定員</label>
                    <input type="number" name="group_capacity" min="2" max="10" value="{{ $group->capacity }}">
                </div>
            
                <div>
                    <label for="genres">Genres:</label>
                    <select id="genres" name="genres[]" multiple required>
                        @foreach ($genres as $genre)
                            <option value="{{ $genre->id }}" @if(in_array($genre->id, $group->genres->pluck('id')->toArray())) selected @endif>{{ $genre->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label for="eras">Eras:</label>
                    <select id="eras" name="eras[]" multiple required>
                        @foreach ($eras as $era)
                            <option value="{{ $era->id }}" @if(in_array($era->id, $group->eras->pluck('id')->toArray())) selected @endif>{{ $era->era }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label for="platforms">Platforms:</label>
                    <select id="platforms" name="platforms[]" multiple required>
                        @foreach ($platforms as $platform)
                            <option value="{{ $platform->id }}" @if(in_array($platform->id, $group->platforms->pluck('id')->toArray())) selected @endif>{{ $platform->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div id="selected-movies" class="mt-3">
                    @foreach ($group->movies as $movie)
                        <!-- Generate each selected movie element -->
                    @endforeach
                </div>
                
                <input type="hidden" id="movies" name="movies" value="{{ implode(',', $group->movies->pluck('tmdb_id')->toArray()) }}">
        
                <div class="form-group mt-3">
                    <button type="submit" class="btn btn-primary">更新</button>
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
            
            <script src="{{ asset('js/editGroup.js') }}"></script>

        </x-app-layout>        
    </body>
</html>