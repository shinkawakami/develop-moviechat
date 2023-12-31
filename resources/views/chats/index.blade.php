<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <title>MovieChat - Chat</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
    <link href="{{ asset('css/chats/index.css') }}" rel="stylesheet">
</head>

<body>
    <x-app-layout>
        <section class="section">
            <div class="container">
                <div class="header-flex">
                    <h1 class="title">チャット</h1>
                    <a href="{{ route('groups.user') }}">戻る</a>
                </div>
                
                <div class="box">
                    <a href="{{ route('groups.show', $group->id) }}" class="group-link">{{ $group->name }}</a>
                    <div class="content">
                        @foreach ($group->messages as $message)
                            <div class="message-item">
                                <div>
                                    <a href="{{ route('profile.show', $message->user) }}">
                                        @if(empty($message->user->image_url))
                                            <i class="fas fa-user icon"></i>
                                        @else
                                            <img src="{{ $message->user->image_url }}" alt="Profile Image" class="icon">
                                        @endif
                                    </a>
                                    <span>{{ $message->user->name }}: {{ $message->content }}</span>
                                </div>
                                <div>
                                    {{ $message->created_at }}
                                    @if($message->user_id == Auth::id())
                                        <form action={{ route('chats.destroy', ['group' => $group->id, 'message' => $message->id]) }} method="POST" class="delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit">削除</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    @foreach ($viewings as $viewing)
                        @if($viewing->is_requester || $viewing->is_recipient)
                            <div class="notification is-primary">
                                {{ $viewing->requester->name }} が {{ $viewing->start_time }} に
                                <a href="{{ route('movies.show', ['movie' => $viewing->movie->tmdb_id]) }}"> {{ $viewing->movie->title }} </a>の同時視聴を申請しています。
                                @foreach ($viewing->approvers as $approver)
                                    <p>{{ $approver->name }}が承認</p>
                                @endforeach
                                @if(!$viewing->is_requester && !$viewing->is_approver)
                                    <form action="{{ route('viewings.approve', ['group' => $group->id, 'viewing' => $viewing->id]) }}" method="POST">
                                        @csrf
                                        <button class="button is-link viewing-button" type="submit">承諾する</button>
                                    </form>
                                @endif
                                <div>
                                    @if($viewing->is_requester || $viewing->is_approver)
                                    <a class="viewing-link" href="{{ $viewing->url }}">視聴先</a>
                                @endif
                                </div>
                                @if($viewing->is_requester)
                                    <form method="POST" action="{{ route('viewings.cancel', ['group' => $group->id, 'viewing' => $viewing->id]) }}">
                                        @csrf
                                        <button class="button is-danger viewing-button" type="submit">申請取り消し</button>
                                    </form>
                                @endif
                            </div>
                        @endif
                    @endforeach
    
                    <form action="{{ route('chats.send', $group->id) }}" method="POST">
                        @csrf
                        <div class="field has-addons">
                            <div class="control is-expanded">
                                <input class="input" type="text" name="message" placeholder="メッセージを入力" required>
                            </div>
                            <div class="control">
                                <button class="button is-info" type="submit">送信</button>
                            </div>
                        </div>
                    </form>
                    
                    <button id="viewing-toggle-btn" class="button is-primary">同時視聴</button>
                        
                    <div id="viewing-section" style="display: none;" class="box">
                        <form action="{{ route('viewings.request', $group->id) }}" method="POST">
                            @csrf
                            <div class="field">
                                <label class="label">申請を送るユーザー</label>
                                <div class="control">
                                    @foreach($group->users as $user)
                                        @if($user->id !== auth()->id())
                                            <label class="checkbox">
                                                <input type="checkbox" name="recipients[]" value="{{ $user->id }}">
                                                {{ $user->name }}
                                            </label>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                           
                            <div class="field">
                                <label class="label">視聴開始時間</label>
                                <input class="input start_time" type="datetime-local" name='start_time'>
                            </div>
                            <br>
                            <label class="label">映画<span class="faint-note">(検索して選択)</span></label>
                            <input type="hidden" id="movie" name="movie">
                            <div id="selected-movie" class="is-info"></div>
                            <div class="field has-addons">
                                <div class="control">
                                    <input class="input" type="text" id="movie-search" placeholder="キーワードを入力">
                                </div>
                                <div class="control">
                                    <button class="button is-info" id="search-btn">検索</button>
                                </div>
                            </div>
        
                            <div id="search-results"></div>
                            
                            <div id="buttons-container" class="buttons-container">
                                <button class="button is-link" type="submit" id="viewing-request-btn">同時視聴申請</button>
                                <button class="button is-light" id="viewing-cancel-btn">キャンセル</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
        
        <script>
            window.groupId = @json($group->id);
        </script>
        <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
        <script src="{{ asset('js/chat.js') }}"></script>
        <script src="{{ asset('js/indexChat.js') }}"></script>
    </x-app-layout>
</body>

</html>
