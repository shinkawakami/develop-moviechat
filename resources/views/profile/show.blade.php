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
                </div>
                
                @if(!$isSelfProfile)
                    <form action="{{ $isFollowing ? route('profile.unfollow', $user) : route('profile.follow', $user) }}" method="POST">
                        @csrf
                        <button type="submit" class="button is-success">
                            {{ $isFollowing ? 'フォロー中' : 'フォローする' }}
                        </button>
                    </form>
                @endif
                
                <br>
                <div class="modal" id="followingsModal">
                  <div class="modal-background"></div>
                  <div class="modal-content">
                    <div class="box">
                        <strong>フォロー</strong>
                        @foreach ($user->followings as $following)
                            <li class="flex-container">
                                <a href="{{ route('profile.show', $following) }}">
                                    @if(empty($following->image_url))
                                        <i class="fas fa-user rounded-icon"></i>
                                    @else
                                        <img src="{{ $following->image_url }}" alt="Profile Image" class="rounded-icon">
                                    @endif
                                </a>
                                <span class="user-name">{{ $following->name }}</span>
                            </li>
                        @endforeach
                    </div>
                  </div>
                  <button class="modal-close is-large" aria-label="close"></button>
                </div>
                
                <div class="modal" id="followersModal">
                  <div class="modal-background"></div>
                  <div class="modal-content">
                    <div class="box">
                        <strong>フォロワー</strong>
                        @foreach ($user->followers as $follower)
                            <li class="flex-container">
                                <a href="{{ route('profile.show', $follower) }}">
                                    @if(empty($follower->image_url))
                                        <i class="fas fa-user rounded-icon"></i>
                                    @else
                                        <img src="{{ $follower->image_url }}" alt="Profile Image" class="rounded-icon">
                                    @endif
                                </a>
                                <span class="user-name">{{ $follower->name }}</span>
                            </li>
                        @endforeach
                    </div>
                  </div>
                  <button class="modal-close is-large" aria-label="close"></button>
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
                        
                        <div class="follow-section">
                            <span id="followingsCount" class="clickable button">フォロー：{{ $user->followings->count() }}</span>
                            <span id="followersCount" class="clickable button">フォロワー：{{ $user->followers->count() }}</span>
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
        <script src="{{ asset('js/showUser.js') }}"></script>
    </x-app-layout>
</body>

</html>
