const url = `/moviechat/groups/${window.groupId}/viewings/${window.viewingId}/notice`;

function checkViewingTime() {
    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.message) {
                let messageElement = document.getElementById('viewing-notification');
                messageElement.innerHTML = data.message;
            }
        });
}

checkViewingTime();

setInterval(checkViewingTime, 5000);
