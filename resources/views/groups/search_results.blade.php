<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <title>MovieChat - Search Results</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    </head>
    <body>
        <x-app-layout>
            <x-slot name="header">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    グループ検索結果
                </h2>
            </x-slot>
            
            <div>
                <h3>検索結果</h3>
                @if ($groups)
                    <div>
                        @foreach ($groups as $group)
                            <p>{{ $group->name }}</p>
                            @foreach ($group->movies as $movie)
                                {{ $movie->title }}　
                            @endforeach
                        @endforeach
                    </div>
                @else
                    <p>検索結果はありません。</p>
                @endif
            </div>
        </x-app-layout>
    </body>
</html>