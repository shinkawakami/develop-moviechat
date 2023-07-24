<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <title>MovieChat</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
        <link href="{{ asset('css/viewings/index.css') }}" rel="stylesheet">
    </head>
    <body>
        <x-app-layout>
        <section class="section">
            <div class="container">
                <h1 class="title">同時視聴用チャット</h1>
                <div class="box">
                    <a class="title" href="{{ route('groups.show', $group->id) }}">{{ $group->name }}</a>
    
                    <div class="content">
                        @foreach ($viewing->messages as $message)
                        <div class="message-item">
                            <span class="message-content">
                                {{ $message->user->name }}: {{ $message->content }}
                            </span>
                            <span class="message-time">{{ $message->created_at }}</span>
                            @if($message->user_id == Auth::id())
                            <form action={{ route('viewings.destroy', ['group' => $group->id, 'viewing' => $viewing->id, 'message' => $message->id]) }} method="POST" class="delete-form">
                                @csrf
                                @method('DELETE')
                                <button class="delete-message" type="submit">削除</button>
                            </form>
                            @endif
                        </div>
                        @endforeach
                    </div>
    
                    <form action={{ route('viewings.chat', ['group' => $group->id, 'viewing' => $viewing->id]) }} method="POST" class="message-send-form">
                        @csrf
                        <div class="field has-addons">
                            <div class="control is-expanded">
                                <input class="input" type="text" name="message" placeholder="メッセージを入力">
                            </div>
                            <div class="control">
                                <button class="button is-info" type="submit">送信</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </x-app-layout>
    </body>
</html>