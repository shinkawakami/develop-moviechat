<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <title>MovieChat - DetailMovie</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="{{ asset('css/movies/show.css') }}" rel="stylesheet">
</head>

<body>
    <x-app-layout>
        <section class="section">
            <div class="container">
                <div class="header-flex">
                    <h1 class="title">映画詳細</h1>
                </div>
                
                <div class="box">
                    <div class="columns">
                        <div class="column is-two-thirds">
                            <h1 class="title movie-title">{{ $movie->title }}</h1>
                            
                            @if(!$movie->posts->isEmpty())
                                <div class="movie-info rating-info">
                                    <span class="icon-container"><i class="fas fa-star"></i></span>
                                    {{ $movie->averageRating() }}
                                </div>
                            @endif
                            
                            @if(isset($movieData['release_date']))
                                <div class="movie-info">
                                    <span class="icon-container"><i class="fas fa-calendar-alt"></i></span>
                                    {{ $movieData['release_date'] }}
                                </div>
                            @endif
                            
                            @if(isset($movieData['genres']) && !empty($movieData['genres']))
                                <div class="movie-info">
                                    <span class="icon-container"><i class="fas fa-tag"></i></span>
                                    @foreach ($movieData['genres'] as $genre)
                                        {{ $genre['name'] }}　
                                    @endforeach
                                </div>
                            @endif
                            
                            @if(isset($movieData['runtime']))
                                <div class="movie-info">
                                    <span class="icon-container"><i class="fas fa-clock"></i></span>
                                    {{ $movieData['runtime'] }} 分
                                </div>
                            @endif
                            
                            @if(isset($movieData['overview']))
                                <div class="movie-info">
                                    <span class="icon-container"><i class="fas fa-info-circle"></i></span>
                                    {{ $movieData['overview'] }}
                                </div>
                            @endif
                            
                            @if(isset($movieData['videos']['results']) && !empty($movieData['videos']['results']))
                                <div class="movie-info">
                                    <span class="icon-container"><i class="fas fa-film"></i></span>
                                    映画予告
                                </div>
                                @foreach ($movieData['videos']['results'] as $video)
                                    @if ($video['type'] == "Trailer")
                                        <a class="button is-link video-link" href="https://www.youtube.com/watch?v={{ $video['key'] }}" target="_blank">{{ $video['name'] }}</a>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                
                        <div class="column">
                            @if(isset($movieData['poster_path']))
                                <img src="https://image.tmdb.org/t/p/w500{{ $movieData['poster_path'] }}" alt="{{ $movieData['title'] }}">
                            @endif
                        </div>
                    </div>
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

