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
                <div>
                    <a href="{{ route('groups.show', $group->id) }}">Group Name:{{ $group->name }}</a>
                </div>
                <p>Group Member
                    @foreach ($group->users as $member)
                        <p>・{{ $member->name }}</p>
                    @endforeach
                </p>
        
                <div id="chat-messages">
                    @foreach ($group->messages as $message)
                        <p>{{ $message->user->name }}: {{ $message->content }}: {{ $message->created_at }}</p>
                        <p>
                            @if($message->user_id == Auth::id())
                            <form action={{ route('chats.destroy', ['group' => $group->id, 'message' => $message->id]) }} method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit">削除</button>
                            </form>
                            @endif
                        </p>
                    @endforeach
                </div>
        
                <form action="/moviechat/groups/{{ $group->id }}/viewings/request" method="POST">
                    @csrf
                    <label for="movie-title">映画タイトル</label>
                    <select name="movie" required>
                        <option value="">映画タイトルを選択してください</option>
                        @foreach ($movies as $movie)
                            <option value="{{ $movie->id }}">{{ $movie->title }}</option>
                        @endforeach
                    </select>
                    <label for="start-time">視聴開始時間</label>
                    <input type="datetime-local" id="start_time" name='start_time' required>
                    <button type="submit">同時視聴申請</button>
                </form>

                <div>
                    @foreach ($viewings as $viewing)
                        <p>{{ $viewing->requester->name }}が同時視聴を希望しています</p>
                        @if(!$viewing->has_approved && !$viewing->is_requester)
                            <form action="/moviechat/groups/{{ $group->id }}/viewings/{{ $viewing->id }}/approve" method="POST">
                                @csrf
                                <button type="submit">同意する</button>
                            </form>
                        @endif
                        @if($viewing->approvers->contains(Auth::id()))
                            @foreach ($viewing->approvers as $approver)
                                <p>{{ $approver->name }}が{{ $viewing->requester->name }}の同時視聴を承認しています</p>
                            @endforeach
                            <p>視聴URL: <a href="{{ $viewing->url }}">同時視聴用チャット先リンク</a></p>
                        @endif
                        @if($viewing->is_requester || $group->is_owner)
                            <form method="POST" action="{{ route('viewings.cancel', ['group' => $group->id, 'viewing' => $viewing->id]) }}">
                                @csrf
                                <button type="submit">申請取り消し</button>
                            </form>
                        @endif
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
                        message.innerText = data.message.user.name + ': ' + data.message.content + ': ' + data.message.created_at;
                        chatMessages.appendChild(message);
                    });
                </script>
        
                <form action="/moviechat/groups/{{ $group->id }}/chats" method="POST">
                    @csrf
                    <input type="text" name="message" placeholder="メッセージを入力">
                    <button type="submit">送信</button>
                </form>
                
                <button onclick="location.href='{{ route('groups.leave', $group->id) }}'">グループを退会する</button>
            </div>
        </x-app-layout>
    </body>
</html>