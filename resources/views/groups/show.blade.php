<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <title>MovieChat - DetailGroup</title>
    <!-- Bulma CSS Link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
    <link href="{{ asset('css/groups/show.css') }}" rel="stylesheet">
</head>

<body>
    <x-app-layout>
        <section class="section">
            <div class="container">
                <div class="box">
                    <h1 class="title">グループ詳細</h1>
                    <div class="content">
                        <p><strong>グループ名　</strong> {{ $group->name }}</p>
                        <p>
                            <strong>グループ人数　</strong> ({{ $group->users->count() }}/{{ $group->capacity }})　
                            @if($group->is_full)
                                <span class="tag is-danger ml-2">満員</span>
                            @endif
                        </p>
                        <p><strong>グループメンバー</strong></p>
                        <ul>
                            @foreach ($group->users as $user)
                            <li class="flex-container">
                                @if(empty($user->image_url))
                                    <i class="fas fa-user rounded-icon"></i>
                                @else
                                    <img src="{{ $user->image_url }}" alt="Profile Image" class="rounded-icon">
                                @endif
                                <span class="user-name">{{ $user->name }}</span>
                                @if ($group->is_owner && Auth::user()->id != $user->id)
                                <form action="{{ route('groups.removeUser', ['group' => $group->id, 'user' => $user->id]) }}" method="POST" class="remove-user-form">
                                    @csrf
                                    @method('DELETE')
                                    <button class="button is-small" type="submit">退会させる</button>
                                </form>
                                @endif
                            </li>
                            @endforeach
                        </ul>
                        <strong class="label-text">好きな映画：</strong> 
                        @foreach ($group->movies as $movie) {{ $movie->title }}　 @endforeach
                        <br>
                        <strong class="label-text">好きなジャンル：</strong>
                        @foreach ($group->genres as $genre) {{ $genre->name }}　 @endforeach
                        <br>
                        <strong class="label-text">好きな年代：</strong>
                        @foreach ($group->eras as $era) {{ $era->era }}　 @endforeach
                        <br>
                        <strong class="label-text">使うプラットフォーム：</strong>
                        @foreach ($group->platforms as $platform) {{ $platform->name }}　 @endforeach
                        <br>
                        
                        <div class="level mt-3">
                            <div class="level-left">
                                @if($group->is_member)
                                    <a href="{{ route('chats.index', $group->id) }}" class="button is-link">チャット</a>
                                    <button onclick="location.href='{{ route('groups.leave', $group->id) }}'" class="button is-dark ml-2">退会</button>
                                @elseif(!$group->is_member && !$group->is_full)
                                    <form action="{{ route('groups.join', $group->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="button is-success">参加</button>
                                    </form>
                                @endif
                            </div>
                            
                            <div class="level-right">
                                @if ($group->is_owner)
                                <a href="{{ route('groups.edit', $group->id) }}" class="button is-warning">編集</a>
                                <form action="/moviechat/groups/{{ $group->id }}" method="POST" onsubmit="return confirmDelete();" class="ml-2">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="button is-dark">グループ削除</button>
                                </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <script>
            function confirmDelete() {
                return confirm('本当に削除しますか？');
            }
        </script>
    </x-app-layout>
</body>

</html>
