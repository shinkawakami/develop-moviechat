<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    
    <head>
        <meta charset="utf-8">
        <title>MovieChat - EditGroup</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
        <link href="{{ asset('css/groups/edit.css') }}" rel="stylesheet">
    </head>
    
    <body>
        <x-app-layout>
            <section class="section">
                <div class="container">
                    <h1 class="title">グループ編集</h1>
        
                    <form action="{{ route('groups.update', $group->id) }}" method="post" class="box">
                        @csrf
                        @method('PUT')
                        <div class="field">
                            <label class="label">グループ名</label>
                            <div class="control">
                                <input name="group_name" type="text" value="{{ $group->name }}" maxlength="50" required class="input" placeholder="グループ名を入力">
                            </div>
                        </div>
                        
                        <div class="field">
                            <label class="label">定員</label>
                            <div class="control">
                                <input name="group_capacity" type="number" value="{{ $group->capacity }}" min="2" max="10" required class="input" placeholder="人数を入力">
                            </div>
                        </div>
                    
                        <div class="columns">
                            <div class="column">
                                <div class="field">
                                    <label class="label">好きなジャンル</label>
                                    <div class="control">
                                        @foreach ($genres as $genre)
                                            <label class="checkbox">
                                                <input type="checkbox" name="genres[]" value="{{ $genre->id }}" {{ in_array($genre->id, $group->genres->pluck('id')->toArray()) ? 'checked' : '' }}>
                                                {{ $genre->name }}
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            
                            <div class="column">
                                <div class="field">
                                    <label class="label">好きな年代</label>
                                    <div class="control">
                                        @foreach ($eras as $era)
                                            <label class="checkbox">
                                                <input type="checkbox" name="eras[]" value="{{ $era->id }}" {{ in_array($era->id, $group->eras->pluck('id')->toArray()) ? 'checked' : '' }}>
                                                {{ $era->era }}
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            
                            <div class="column">
                                <div class="field">
                                    <label class="label">使うプラットフォーム</label>
                                    <div class="control">
                                        @foreach ($platforms as $platform)
                                            <label class="checkbox">
                                                <input type="checkbox" name="platforms[]" value="{{ $platform->id }}" {{ in_array($platform->id, $group->platforms->pluck('id')->toArray()) ? 'checked' : '' }}>
                                                {{ $platform->name }}
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="field">
                            <label class="label">好きな映画<span class="faint-note">(検索して選択 - 複数選択可能)</span></label>
                            <div class="control">
                                <span id="selected-movies"></span>
                                <div id="movies"></div>
                            </div>
                        </div>
    
                        <div class="field is-grouped is-grouped-right">
                            <div class="control">
                                <button type="submit" class="button is-primary">更新</button>
                            </div>
                        </div>
                    </form>
                    
                    <form class="box">
                        <h2 class="subtitle movie-search-title">映画検索</h2>
                        <div class="field has-addons">
                            <div class="control">
                                <input type="text" id="movie-search" class="input" placeholder="キーワードを入力">
                            </div>
                            <div class="control">
                                <button id="search-btn" class="button is-info">検索</button>
                            </div>
                        </div>
                    
                        <div id="search-results"></div>
                    </form>
                </div>
            </section>
            
            <script>
                window.groupMovies = @json($group->movies);
            </script>
            <script src="{{ asset('js/editGroup.js') }}"></script>
        </x-app-layout>        
    </body>
</html>