// Pusherの初期化
const pusher = new Pusher(window.PUSHER_APP_KEY, {
  cluster: window.PUSHER_APP_CLUSTER,
  encrypted: true
});

// このユーザーが現在のグループに参加しているチャンネルを購読
var channel = pusher.subscribe('group.' + window.CURRENT_GROUP_ID);

channel.bind('App\\Events\\MessageSent', function(data) {
    var messageElement = `
        <div class="message-item">
            <span class="message-content">
                <span><img src="${data.message.user.image_url}" alt="Profile Image" class="rounded-icon"></span>
                <span>${data.message.user.name}: ${data.message.content}</span>
            </span>
            <span class="message-time">${data.message.created_at}</span>
            // その他のコード...
        </div>
    `;
    
    document.querySelector(".content").innerHTML += messageElement;
});