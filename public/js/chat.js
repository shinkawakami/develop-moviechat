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
            let deleteButton = '';
            if (data.message.user_id == window.authId) {
                deleteButton = `
                    <form action="/moviechat/groups/${window.groupId}/chats/${data.message.id}" method="POST" class="delete-form">
                        <input type="hidden" name="_token" value="${window.csrfToken}">  
                        <input type="hidden" name="_method" value="DELETE">
                        <button class="delete-message" type="submit">削除</button>
                    </form>
                `;
            }

            const messageElement = `
                <div class="message-item">
                    <div class="message-content">
                        <img src="${data.message.user.image_url}" alt="Profile Image" class="rounded-icon">
                        <span>${data.message.user.name}: ${data.message.content}</span>
                    </div>
                    <div class="message-time">
                        ${data.message.created_at}
                        ${deleteButton}
                    </div>
                </div>
            `;

            document.querySelector(".content").innerHTML += messageElement;
        });

    })
    
