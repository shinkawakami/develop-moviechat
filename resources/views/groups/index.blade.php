<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <title>MovieChat - IndexGroups</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
    <link href="{{ asset('css/groups/index.css') }}" rel="stylesheet">
</head>

<body>
    <x-app-layout>
        <section class="section">
            <div class="container">
                @if(request()->is('*search*'))
                    <div class="header-flex">
                        <h1 class="title">検索結果</h1>
                        <a href="{{ route('groups.showSearch') }}">戻る</a>
                    </div>
                @else
                    <h1 class="title">グループ一覧</h1>
                @endif
                
                @if(!$groups->isEmpty())
                    @foreach($groups as $group)
                        <div class="box">
                            <a href="{{ route('groups.show', $group->id) }}" class="group-link">{{ $group->name }}</a>
                            <p>
                                <span class="label-text">好きな映画：</span>
                                @foreach ($group->movies as $movie)
                                <span class="tag is-black">{{ $movie->title }}</span>
                                @endforeach
                            </p>
                            <p>
                                <span class="label-text">好きなジャンル：</span>
                                @foreach ($group->genres as $genre)
                                <span class="tag is-primary">{{ $genre->name }}</span>
                                @endforeach
                            </p>
                            <p>
                                <span class="label-text">好きな年代：</span>
                                @foreach ($group->eras as $era)
                                <span class="tag is-info">{{ $era->era }}</span>
                                @endforeach
                            </p>
                            <p>
                                <span class="label-text">使うプラットフォーム：</span>
                                @foreach ($group->platforms as $platform)
                                <span class="tag is-warning">{{ $platform->name }}</span>
                                @endforeach
                            </p>
                            
                            @if($group->is_full)
                            <div class="tag is-danger">満員</div>
                            @endif
                            
                            <div class="buttons">
                                @if($group->is_member)
                                    <a href="{{ route('chats.index', $group->id) }}" class="button is-link">チャット</a>
                                @elseif(!$group->is_member && !$group->is_full)
                                    <form action="{{ route('groups.join', $group->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="button is-success">参加</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="box">
                        <p>該当なし</p>
                    </div>
                @endif
            </div>
        </section>
    </x-app-layout>
</body>

</html>
