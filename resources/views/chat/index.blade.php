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
                <p>Group Name: {{ $group->name }}</p>
                <p>Group Member
                    @foreach ($group->users as $member)
                        <p>・{{ $member->name }}</p>
                    @endforeach
                </p>
                
                
                
                <div id="chat-messages">
                    @foreach ($group->messages as $message)
                        <p>{{ $message->user->name }}: {{ $message->content }}: {{ $message->created_at }}</p>
                    @endforeach
                </div>
                
                <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
                <script>
                    window.Pusher = new Pusher('{{ env("PUSHER_APP_KEY") }}', {
                        cluster: '{{ env("PUSHER_APP_CLUSTER") }}',
                        forceTLS: true
                    });
                    var channel = window.Pusher.subscribe('group-chat.{{ $group->id }}');
                    channel.bind('.message.sent', function(data) {
                        const chatMessages = document.getElementById('chat-messages');
                        const message = document.createElement('p');
                        message.innerText = ': ' + data.message.content + ': ' + data.message.created_at;
                        chatMessages.appendChild(message);
                    });
                </script>
                                
                <form action="/moviechat/group/{{ $group->id }}/chat" method="POST">
                    @csrf
                    <input type="text" name="message" placeholder="メッセージを入力">
                    <button type="submit">送信</button>
                </form>
                
            </div>
        </x-app-layout>
    </body>
</html>