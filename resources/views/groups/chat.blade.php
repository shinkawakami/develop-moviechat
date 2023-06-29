<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <title>Movie</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    </head>
    <body>
        <x-app-layout>
            <x-slot name="header">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Chat
                </h2>
            </x-slot>
            <div class='button'>
                <div class='group_make_page'>
                    <a href="/movies/make">グループ作成</a>
                </div>
                <div class='group_search_page'>
                    <a href="/movies/search">グループ検索</a>
                </div>
                <div class='group_showlist_page'>
                    <a href="/movies/showlist">グループ一覧</a>
                </div>
            </div>
            <div>
                <p>Group Name: {{ $group->name }}</p>
                <p>Group Member
                    @foreach ($group->users as $member)
                        <p>・{{ $member->name }}</p>
                    @endforeach
                </p>
                <p>Movie: {{ $group->movie->title }}</p>
                
                @foreach ($group->messages as $message)
                    <p>{{ $message->user->name }}: {{ $message->content }}: {{ $message->created_at }}</p>
                @endforeach
                
                <form action="/movies/groups/{{ $group->id }}/chat" method="POST">
                    @csrf
                    <input type="text" name="message" placeholder="メッセージを入力">
                    <button type="submit">送信</button>
                </form>
                
            </div>
        </x-app-layout>
    </body>
</html>