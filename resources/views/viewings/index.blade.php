<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <title>MovieChat</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    </head>
    <body>
        <x-app-layout>
            <x-slot name="header">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Chat
                </h2>
            </x-slot>
            
            <div>
                <p>映画：{{ $viewing->movie->title }}</p>
                <p>視聴者：
                    {{ $viewing->requester->name }}
                    @foreach ($viewing->approvers as $member)
                        <p>・{{ $member->name }}</p>
                    @endforeach
                </p>
                
                <div id="chat-messages">
                    @foreach ($viewing->messages as $message)
                        <p>{{ $message->user->name }}: {{ $message->content }}: {{ $message->created_at }}</p>
                    @endforeach
                </div>
                                
                <form action="/moviechat/groups/{{ $group->id }}/viewings/{{ $viewing->id }}/chats" method="POST">
                    @csrf
                    <input type="text" name="message" placeholder="メッセージを入力" required maxlength="255">
                    <button type="submit">送信</button>
                </form>
                
            </div>
        </x-app-layout>
    </body>
</html>