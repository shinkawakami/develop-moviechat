<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <title>MovieChat - SearchGroups</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
    <link href="{{ asset('css/groups/search.css') }}" rel="stylesheet">
</head>

<body>
    <x-app-layout>
        <section class="section">
            <div class="container">
                <h1 class="title">グループ検索</h1>
                
                <!-- Keyword Search -->
                <div class="box">
                    <h2 class="subtitle">キーワード検索</h2>
                    <form method="GET" action="{{ route('groups.searchResults') }}" class="field has-addons">
                        <div class="control">
                            <input type="text" name="keyword" required maxlength="50" class="input" placeholder="キーワードを入力">
                        </div>
                        <div class="control">
                            <input type="submit" value="検索" class="button is-info">
                        </div>
                    </form>
                </div>

                <!-- Conditional Search -->
                <div class="box">
                    <h2 class="subtitle">条件検索</h2>
                    <form method="GET" action="{{ route('groups.searchResults') }}">
                        <div class="columns">

                            <!-- Genre Column -->
                            <div class="column">
                                <div class="field">
                                    <label class="label">ジャンル</label>
                                    <div class="control">
                                        @foreach ($genres as $genre)
                                            <label class="checkbox">
                                                <input type="checkbox" name="genres[]" value="{{ $genre->id }}">
                                                {{ $genre->name }}
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- Era Column -->
                            <div class="column">
                                <div class="field">
                                    <label class="label">年代</label>
                                    <div class="control">
                                        @foreach ($eras as $era)
                                            <label class="checkbox">
                                                <input type="checkbox" name="eras[]" value="{{ $era->id }}">
                                                {{ $era->era }}
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- Platform Column -->
                            <div class="column">
                                <div class="field">
                                    <label class="label">プラットフォーム</label>
                                    <div class="control">
                                        @foreach ($platforms as $platform)
                                            <label class="checkbox">
                                                <input type="checkbox" name="platforms[]" value="{{ $platform->id }}">
                                                {{ $platform->name }}
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                        </div> <!-- end columns -->

                        <div class="field">
                            <div class="control">
                                <input type="submit" value="検索" class="button is-primary">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </x-app-layout>
</body>

</html>
