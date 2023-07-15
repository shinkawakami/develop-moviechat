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
                @foreach($groups as $group)
                    <div>
                        <a href="{{ route('groups.show', $group->id) }}">{{ $group->name }}</a>
                    </div>
                    @foreach($group->movies as $movie)
                        <h3>Movie: {{ $movie->title }}</h3>
                    @endforeach
                    @if($group->is_member)
                        <a href="{{ route('chats.index', $group->id) }}">
                            <button>チャット</button>
                        </a>
                    @endif
                    @if(!$group->is_member && $group->users->count() < $group->capacity)
                    <form action="{{ route('groups.join', $group->id) }}" method="POST">
                        @csrf
                        <button type="submit">参加</button>
                    </form>
                    @endif
                @endforeach
            </div>
        </x-app-layout>
    </body>
</html>