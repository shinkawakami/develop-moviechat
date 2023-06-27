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
                    Group Details
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
                    @foreach ($group->users as $user)
                        <p>・{{ $user->name }}</p>
                    @endforeach</p>
                </div>
                <form action="{{ route('joinGroup', ['group' => $group->id]) }}" method="POST">
                    @csrf
                    <button type="submit">join</button>
                </form>
            </div>
        </x-app-layout>
    </body>
</html>