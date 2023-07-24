<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <title>MovieChat - EditPost</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
        <link href="{{ asset('css/posts/create.css') }}" rel="stylesheet">
    </head>
    <body>
        <x-app-layout>
             <section class="section">
                <div class="container">
                    <h1 class="title">投稿編集</h1>
        
                    <form method="POST" action="{{ route('posts.update', $post) }}" class="box">
                        @csrf
                        @method('PUT')
                        
                        <div class="field">
                            <input class="input" type="text" name="title" placeholder="タイトル" value="{{ $post->title }}" maxlength="50" required>
                        </div>
        
                        <label class="label">映画<span class="faint-note">(検索して選択)</span></label>
                        <div id="selected-movie" class="field"></div>
        
                        <input type="hidden" name="movie" id="movie">
        
                        <div class="field">
                            <textarea class="textarea" name="content" placeholder="内容" maxlength="255" required>{{ $post->content }}</textarea>
                        </div>
        
                        <div class="field">
                            <input class="button is-primary" type="submit" value="更新">
                        </div>
                    </form>
        
                    <form class="box">
                        <div class="field has-addons">
                            <div class="control is-expanded">
                                <input class="input" type="text" placeholder="映画検索" id="movie-search">
                            </div>
                            <div class="control">
                                <button id="search-btn" class="button is-info">検索</button>
                            </div>
                        </div>
                    </form>
        
                    <div id="search-results"></div>
                </div>
            </section>
        </x-app-layout>

        <script>
            window.postMovieId = @json($post->movie->tmdb_id);
            window.postMovieTitle = @json($post->movie->title);
        </script>
        <script src="{{ asset('js/editPost.js') }}"></script>
      
    </body>
</html>
