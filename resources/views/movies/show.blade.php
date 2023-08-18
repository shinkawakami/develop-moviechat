<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <title>MovieChat - DetailMovie</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
    <link href="{{ asset('css/movies/show.css') }}" rel="stylesheet">
</head>

<body>
    <x-app-layout>
        <section class="section">
            <div class="container">
                <div class="header-flex">
                    <h1 class="title">映画詳細</h1>
                    <a class="return-button" href="{{ route('movies.index') }}">戻る</a>
                </div>
                
                <div class="box">
                    <h2 class="subtitle">{{ $movie->title }}</h2>
                </div>

                <div class="box">
                    <h2 class="subtitle">関連するグループ</h2>
                    @if(!$movie->groups->isEmpty())
                    <div class="columns is-multiline">
                        @foreach($movie->groups as $group)
                        <div class="column is-one-quarter">
                            <a href="{{ route('groups.show', ['group' => $group->id ]) }}" class="card-link">
                                <div class="card">
                                    <header class="card-header">
                                        <p class="card-header-title">{{ $group->name }}</p>
                                    </header>
                                </div>
                            </a>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p>該当なし</p>
                    @endif
                </div>
              
                <div class="box">
                    <h2 class="subtitle">関連する投稿</h2>
                    @if(!$movie->posts->isEmpty())
                    <div class="columns is-multiline">
                        @foreach($movie->posts as $post)
                        <div class="column is-one-quarter">
                            <a href="{{ route('posts.show', ['post' => $post->id ]) }}" class="card-link">
                                <div class="card">
                                    <header class="card-header">
                                        <p class="card-header-title">{{ $post->title }}</p>
                                    </header>
                                </div>
                            </a>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p>該当なし</p>
                    @endif
                </div>
            </div>
        </section>
    </x-app-layout>
</body>

</html>

