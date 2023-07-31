// Pusherの初期化
const pusher = new Pusher(window.PUSHER_APP_KEY, {
  cluster: window.PUSHER_APP_CLUSTER,
  encrypted: true
});

// このユーザーが現在のグループに参加しているチャンネルを購読
var channel = pusher.subscribe('group.' + window.CURRENT_GROUP_ID);

channel.bind('App\\Events\\MessageSent', function(data) {
    var deleteButton = '';
    if (data.message.user_id == window.AUTH_ID) {  
        deleteButton = `
        <form action="/moviechat/groups/${window.CURRENT_GROUP_ID}/chats/${data.message.id}" method="POST" class="delete-form">
            <input type="hidden" name="_token" value="${window.CSRF_TOKEN}">  
            <input type="hidden" name="_method" value="DELETE">
            <button class="delete-message" type="submit">削除</button>
        </form>
        `;
    }
    
    var messageElement = `
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