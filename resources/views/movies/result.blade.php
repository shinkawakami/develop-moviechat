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
                    Result Movie
                </h2>
            </x-slot>
            
            <div>
                @foreach ($movies as $movie)
                    <div>
                        <p>{{ $movie->title }}</p>
                        <p>
                            @foreach ($movie->genres as $genre)
                                ・{{ $genre->name }}
                            @endforeach
                            @foreach ($movie->platforms as $platform)
                                ・{{ $platform->name }}
                            @endforeach
                                ・{{ $movie->year }}
                        </p>
                        @foreach ($movie->groups as $group) 
                            <a href="/moviechat/group/{{ $group->id }}">・{{ $group->name }}</a>  
                        @endforeach
                    </div>
                @endforeach
                
            </div>
        </x-app-layout>
    </body>
</html>