<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <title>MovieChat - IndexGroup</title>
    <!-- BulmaのCSSを追加 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
    <link href="{{ asset('css/groups/index.css') }}" rel="stylesheet">
</head>

<body>
    <x-app-layout>
        <section class="section">
            <div class="container">
                <h1 class="title">グループ一覧</h1>
                
                @foreach($groups as $group)
                <div class="box">
                    <a href="{{ route('groups.show', $group->id) }}" class="group-link">{{ $group->name }}</a>
                    <p>
                        好きな映画：
                        @foreach ($group->movies as $movie)
                        <span class="tag is-danger">{{ $movie->title }}</span>
                        @endforeach
                    </p>
                    <p>
                        好きなジャンル：
                        @foreach ($group->genres as $genre)
                        <span class="tag is-primary">{{ $genre->name }}</span>
                        @endforeach
                    </p>
                    <p>
                        好きな年代：
                        @foreach ($group->eras as $era)
                        <span class="tag is-info">{{ $era->era }}</span>
                        @endforeach
                    </p>
                    <p>
                        使うプラットフォーム：
                        @foreach ($group->platforms as $platform)
                        <span class="tag is-warning">{{ $platform->name }}</span>
                        @endforeach
                    </p>
                    
                    @if($group->users->count() == $group->capacity)
                    <div class="tag is-danger">満員</div>
                    @endif
                    
                    <div class="buttons">
                        @if($group->is_member)
                        <a href="{{ route('chats.index', $group->id) }}" class="button is-link">チャット</a>
                        @elseif(!$group->is_member && $group->users->count() < $group->capacity)
                        <form action="{{ route('groups.join', $group->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="button is-success">参加</button>
                        </form>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </section>
    </x-app-layout>
</body>

</html>
