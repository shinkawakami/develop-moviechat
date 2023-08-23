// 30秒ごとに視聴開始時間をチェックして，5分前と開始時間に通知する
setInterval(function() {
    const url = `/moviechat/groups/${window.groupId}/viewings/${window.viewingId}/notice`;
    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.message) {
                let messageElement = document.getElementById('viewing-notification');
                messageElement.innerHTML = data.message;
            }
        })
        .catch(error => {
            console.error('Fetch error: ', error);
        });
}, 30000);