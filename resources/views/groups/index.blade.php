<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <title>MovieChat - index</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    </head>
    <body>
        <x-app-layout>
            <x-slot name="header">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    グループ一覧
                </h2>
            </x-slot>
            
            <div>
                @foreach ($groups as $group)
                    <div>
                      <a href="/moviechat/groups/{{ $group->id }}{{ $group->is_member ? '/chat' : '' }}">・{{ $group->name }}</a>  
                    </div>
                @endforeach
                @foreach($groups as $group)
                    <h2>Group: {{ $group->name }}</h2>
                    @foreach($group->movies as $movieId => $movieDetails)
                        <h3>Movie: {{ $movieDetails['title'] }}</h3>
                        <p>Overview: {{ $movieDetails['overview'] }}</p>
                        <p>Release Date: {{ $movieDetails['release_date'] }}</p>
                    @endforeach
                @endforeach
            </div>
        </x-app-layout>
    </body>
</html>