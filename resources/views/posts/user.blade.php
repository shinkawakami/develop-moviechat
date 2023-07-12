<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <title>MovieChat - My Posts</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    </head>
    <body>
        <x-app-layout>
            <x-slot name="header">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    自分の投稿
                </h2>
            </x-slot>
            <div>
                @foreach ($posts as $post)
                    <p>{{ $post->title }}</p>
                    <p>{{ $post->user->name }}</p>
                    <p>{{ $post->movie->title }}</p>
                    <p>{{ $post->content }}</p>
                @endforeach
            </div>
        </x-app-layout>
    </body>
</html>
