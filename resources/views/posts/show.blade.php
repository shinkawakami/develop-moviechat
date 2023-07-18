<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <title>MovieChat - Post Detail</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    </head>
    <body>
        <x-app-layout>
            <x-slot name="header">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    投稿詳細
                </h2>
            </x-slot>
            <div>
                <p>{{ $post->title }}</p>
                <p>{{ $post->user->name }}</p>
                @if($post->movie)
                    <p>{{ $post->movie->title }}</p>
                @endif
                <p>{{ $post->content }}</p>
                <p>
                    @if (Auth::user()->id == $post->user_id)
                        <form action="{{ route('posts.destroy', $post->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit">削除</button>
                        </form>
                        <div>
                            <a href="{{ route('posts.edit', $post->id) }}">編集</a>
                        </div>
                    @endif
                </p>
            </div>
            <!-- コメント表示 -->
            @foreach ($post->comments as $comment)
                <p>{{ $comment->user->name }}</p>
                <p>{{ $comment->content }}</p>
            @endforeach
            <!-- コメント投稿フォーム -->
            <form method="POST" action="{{ route('posts.comment', $post->id) }}">
                @csrf
                <textarea name="comment" placeholder="コメント" maxlength="255" required></textarea>
                <input type="submit" value="コメント投稿">
            </form>
        </x-app-layout>
    </body>
</html>
