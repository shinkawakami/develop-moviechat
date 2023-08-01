// グループIDの取得
const linkElement = document.querySelector('a[href^="{{ route('groups.show') }}"]');
const groupId = linkElement.href.split('/').pop();

// Pusherの初期化
const pusher = new Pusher(pusherAppKe, {
    cluster: pusherAppCluster,
    encrypted: true
});

// このユーザーが現在のグループに参加しているチャンネルを購読
const channel = pusher.subscribe('group.' + groupId);

// データの取得
fetch('/moviechat/groups/receive')
    .then(response => response.json())
    .then(data => {
        authId = data.auth_id;
        csrfToken = data.csrf_token;
        pusherAppKey = data.pusher_app_key;
        pusherAppCluster = data.pusher_app_cluster;
    })

channel.bind('App\\Events\\MessageSent', function(data) {
    let deleteButton = '';
    if (data.message.user_id == authId) {
        deleteButton = `
            <form action="/moviechat/groups/${groupId}/chats/${data.message.id}" method="POST" class="delete-form">
                <input type="hidden" name="_token" value="${csrfToken}">  
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