<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <title>MovieChat</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    </head>
    <body>
        <x-app-layout>
            <x-slot name="header">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Movie List
                </h2>
            </x-slot>
            <div>
                
                    <div>
                        <p>{{ $movie['title'] }}
                        
                        </p>
                        
                        <p>{{ $movie['overview'] }}</p>
                        <img src="https://image.tmdb.org/t/p/w500{{ $movie['poster_path'] }}" alt="{{ $movie['title'] }} Poster">
                    </div>
                
            </div>
        </x-app-layout>
    </body>
</html>