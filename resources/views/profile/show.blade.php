<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <title>MovieChat - DetailGroup</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
    <link href="{{ asset('css/auth/show.css') }}" rel="stylesheet">
</head>

<body>
    <x-app-layout>
        <section class="section">
            <div class="container">
                <div class="header-flex">
                    <h1 class="title">プロフィール</h1>
                    <a href="{{ url()->previous() }}">戻る</a>
                </div>
                
                <div class="box">
                    <div class="content">
                        <div class="flex-container">
                            @if(empty($user->image_url))
                                <i class="fas fa-user rounded-icon"></i>
                            @else
                                <img src="{{ $user->image_url }}" alt="Profile Image" class="rounded-icon">
                            @endif
                            <span class="user-name">{{ $user->name }}</span>
                        </div>
                        
                        <br>
                        <div class="box">
                            <p>{{ $user->introduction }}</p>
                        </div>
                        <strong class="label-text">好きな映画：</strong> 
                        @foreach ($user->movies as $movie) {{ $movie->title }}　 @endforeach
                        <br>
                        <strong class="label-text">好きなジャンル：</strong>
                        @foreach ($user->genres as $genre) {{ $genre->name }}　 @endforeach
                        <br>
                        <strong class="label-text">好きな年代：</strong>
                        @foreach ($user->eras as $era) {{ $era->era }}　 @endforeach
                        <br>
                        <strong class="label-text">使うプラットフォーム：</strong>
                        @foreach ($user->platforms as $platform) {{ $platform->name }}　 @endforeach
                        <br><br>
                        
                        <div class="box">
                            <h2>グループ</h2>
                            @if(!$user->groups->isEmpty())
                            <div class="columns is-multiline">
                                @foreach($user->groups as $group)
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
                            <h2>投稿</h2>
                            @if(!$user->posts->isEmpty())
                            <div class="columns is-multiline">
                                @foreach($user->posts as $post)
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
                </div>
            </div>
        </section>
    </x-app-layout>
</body>

</html>
