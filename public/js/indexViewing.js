const url = `/moviechat/groups/${window.groupId}/viewings/${window.viewingId}/notice`;

let interval;

function checkViewingTime() {
    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.message) {
                let messageElement = document.getElementById('viewing-notification');
                messageElement.innerHTML = data.message;
            }
            
            if (data.status !== '視聴前') {
                clearInterval(interval);
            }
        });
}

if (window.viewingStatus != '視聴終了') {
    checkViewingTime();
    interval = setInterval(checkViewingTime, 30000);
}
