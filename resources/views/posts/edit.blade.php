<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <title>MovieChat - Edit Post</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    </head>
    <body>
        <x-app-layout>
            <x-slot name="header">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    投稿編集
                </h2>
            </x-slot>
            <!-- 投稿編集フォーム -->
            <div>
                <form method="POST" action="{{ route('posts.update', $post->id) }}">
                    @csrf
                    @method('PUT')
                    <input type="text" name="title" value="{{ $post->title }}">
                    <input type="text" name="movie_title" value="{{ $post->movie->title }}">
                    <textarea name="content">{{ $post->content }}</textarea>
                    <input type="submit" value="更新">
                </form>
            </div>
        </x-app-layout>
    </body>
</html>
