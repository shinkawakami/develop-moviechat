// データの取得
const url = '/moviechat/groups/receive';
fetch(url)
    .then(response => response.json())
    .then(data => {
        window.authId = data.auth_id;
        window.csrfToken = data.csrf_token;
        window.pusherAppKey = data.pusher_app_key;
        window.pusherAppCluster = data.pusher_app_cluster;

        // Pusherの初期化をデータの取得が完了した後に行う
        const pusher = new Pusher(window.pusherAppKey, {
            cluster: window.pusherAppCluster,
            encrypted: true
        });

        // このユーザーが現在のグループに参加しているチャンネルを購読
        const channel = pusher.subscribe('group.' + window.groupId);

        channel.bind('App\\Events\\MessageSent', function(data) {
           

            

            const messageElement = `
                <div class="message-item">
                    <div class="message-content">
                        
                        <span>${data.message.user.name}: ${data.message.content}</span>
                    </div>
                    <div class="message-time">
                        ${data.message.created_at}
                        
                    </div>
                </div>
            `;

            document.querySelector(".content").innerHTML += messageElement;
        });
    })
