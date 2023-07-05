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
                <p>グループ名：{{ $group->name }}</p>
                <p>グループ人数：({{ $group->users->count() }}/{{ $group->capacity }})</p>
                <p>グループメンバー：
                    @foreach ($group->users as $user)
                        {{ $user->name }}　
                    @endforeach
                </p>
                <p>選択してる映画：
                    @foreach ($group->movies as $movie)
                        {{ $movie->title }}　
                    @endforeach
                </p>
                <p>好きなジャンル：
                    @foreach ($group->genres as $genre)
                        {{ $genre->name }}　
                    @endforeach
                </p>
                <p>使うプラットフォーム：
                    @foreach ($group->platforms as $platform)
                        {{ $platform->name }}　
                    @endforeach
                </p>
                <p>好きな年代：
                    @foreach ($group->eras as $era)
                        {{ $era->era }}　
                    @endforeach
                </p>
                
                @php
                    $isMember = $group->users->contains(Auth::id());
                @endphp
                
                @if (!$isMember && $group->users->count() < $group->capacity)
                    <form action="/moviechat/group/{{ $group->id }}" method="POST">
                        @csrf
                        <button type="submit">参加</button>
                    </form>
                @endif
                
                @if (Auth::id() === $group->creator_id)
                    <form action="/moviechat/group/{{ $group->id }}" method="POST" onsubmit="return confirmDelete();">
                        @csrf
                        @method('DELETE')
                        <button type="submit">グループ削除</button>
                    </form>
                @endif
            </div>
            <script>
                function confirmDelete() {
                    return confirm('本当に削除しますか？');
                }
            </script>
        </x-app-layout>
    </body>
</html>