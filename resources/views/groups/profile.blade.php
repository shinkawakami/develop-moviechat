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
                    Group Profile
                </h2>
            </x-slot>
            
            <div>
                <p>Group Name: {{ $group->name }}</p>
                <p>Group Member
                    @foreach ($group->users as $user)
                        <p>・{{ $user->name }}</p>
                    @endforeach
                </p>
                <p>Movie
                    @foreach ($group->movies as $movie)
                        <p>・{{ $movie->title }}</p>
                    @endforeach
                </p>
                <p>Genre
                    @foreach ($group->genres as $genre)
                        <p>・{{ $genre->name }}</p>
                    @endforeach
                </p>
                <p>Platform
                    @foreach ($group->platforms as $platform)
                        <p>・{{ $platform->name }}</p>
                    @endforeach
                </p>
                <p>Genre
                    @foreach ($group->eras as $era)
                        <p>・{{ $era->era }}</p>
                    @endforeach
                </p>
                <form action="/moviechat/group/{{ $group->id }}" method="POST">
                    @csrf
                    <button type="submit">join</button>
                </form>
            </div>
        </x-app-layout>
    </body>
</html>