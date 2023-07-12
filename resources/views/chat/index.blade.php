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
        
                <form action="/moviechat/group/{{ $group->id }}/request" method="POST">
                    @csrf
                    <label for="movie-title">映画タイトル</label>
                    <select name="movie_id" required>
                        <option value="">映画タイトルを選択してください</option>
                        @foreach ($movies as $movie)
                            <option value="{{ $movie->id }}">{{ $movie->title }}</option>
                        @endforeach
                    </select>
                    <label for="start-time">視聴開始時間</label>
                    <input type="datetime-local" id="start_time" name='start_time' required>
                    <button type="submit">同時視聴申請</button>
                </form>

                <div id="view-requests">
                    @foreach ($viewGroups as $viewGroup)
                        <p>{{ $viewGroup->requester->name }}が同時視聴を希望しています</p>
                        @if(!$viewGroup->has_approved && !$viewGroup->is_requester)
                            <form action="/moviechat/group/{{ $group->id }}/approve/{{ $viewGroup->id }}" method="POST">
                                @csrf
                                <button type="submit">同意する</button>
                            </form>
                        @endif
                        @if($viewGroup->approvers->contains(Auth::id()))
                            @foreach ($viewGroup->approvers as $approver)
                                <p>{{ $approver->name }}が{{ $viewGroup->requester->name }}の同時視聴を承認しています</p>
                            @endforeach
                            <p>視聴URL: <a href="{{ $viewGroup->view_link }}">同時視聴用チャット先リンク</a></p>
                        @endif
                        @if($viewGroup->is_requester)
                            <form method="POST" action="{{ route('view.cancel', ['groupId' => $group->id, 'viewGroupId' => $viewGroup->id]) }}">
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
        
                <form action="/moviechat/groups/{{ $group->id }}/chat" method="POST">
                    @csrf
                    <input type="text" name="message" placeholder="メッセージを入力">
                    <button type="submit">送信</button>
                </form>
                
                <button onclick="location.href='{{ route('groups.leave', $group->id) }}'">グループを退会する</button>
            </div>
        </x-app-layout>
    </body>
</html>