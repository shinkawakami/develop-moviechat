function formatDateTime(dateTimeStr) {
    return dateTimeStr.replace('T', ' ').split('.')[0];
}

// データの取得
const url = '/moviechat/groups/${window.groupId}/chats/receive';
fetch(url)
    .then(response => response.json())
    .then(data => {
        window.authId = data.auth_id;
        window.csrfToken = data.csrf_token;
        window.pusherAppKey = data.pusher_app_key;
        window.pusherAppCluster = data.pusher_app_cluster;

        // Pusherの初期化
        const pusher = new Pusher(window.pusherAppKey, {
            cluster: window.pusherAppCluster,
            encrypted: true
        });

        // Pusherを用いてメッセージの取得と表示
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
            
            const icon = data.message.user.image_url ? 
                `<img src="${data.message.user.image_url}" alt="Profile Image" class="icon">` : 
                '<i class="fas fa-user icon"></i>';
                
            const dateTime = formatDateTime(data.message.created_at);
            
            const messageElement = `
                <div class="message-item">
                    <div">
                        ${icon}
                        <span>${data.message.user.name}: ${data.message.content}</span>
                    </div>
                    <div>
                        ${dateTime}
                        ${deleteButton}
                    </div>
                </div>
            `;

            document.querySelector(".content").innerHTML += messageElement;
        });
    })
