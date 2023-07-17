<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <title>MovieChat - {{ $movie['title'] }}</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    </head>
    <body>
        <x-app-layout>
            <x-slot name="header">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    グループ詳細
                </h2>
            </x-slot>
            <div class="container mt-4">
                <h1>{{ $movie['title'] }}</h1>
                <!-- Display movie details here -->
                <h2>Related Groups</h2>
                @foreach($groups as $group)
                    <!-- Display each group details here -->
                    <h1><a href="{{ route('groups.show', ['group' => $group->id ]) }}">{{ $group->name }}</a></h1>
                @endforeach
                <h2>Related Posts</h2>
                @foreach($posts as $post)
                    <!-- Display each post details here -->
                    <h1><a href="{{ route('posts.show', ['post' => $post->id ]) }}">{{ $post->title }}</a></h1>
                @endforeach
            </div>
        </x-app-layout>
    </body>
</html>
