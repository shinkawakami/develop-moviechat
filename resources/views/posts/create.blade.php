<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <title>MovieChat - Create Post</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    </head>
    <body>
        <x-app-layout>
            <x-slot name="header">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    投稿作成
                </h2>
            </x-slot>
            <!-- 投稿作成フォーム -->
            <div>
                <form method="POST" action="{{ route('posts.store') }}">
                    @csrf
                    <input type="text" name="title" placeholder="タイトル">
                    <input type="text" name="movie_id" placeholder="映画ID">
                    <textarea name="content" placeholder="内容"></textarea>
                    <input type="submit" value="投稿">
                </form>
            </div>
        </x-app-layout>
    </body>
</html>
