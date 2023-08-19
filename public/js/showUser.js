document.addEventListener('DOMContentLoaded', function() {
    const followingsCount = document.getElementById('followingsCount');
    const followersCount = document.getElementById('followersCount');
    const followingsModal = document.getElementById('followingsModal');
    const followersModal = document.getElementById('followersModal');

    followingsCount.addEventListener('click', function() {
        followingsModal.classList.add('is-active');
    });

    followersCount.addEventListener('click', function() {
        followersModal.classList.add('is-active');
    });

    document.querySelectorAll('.modal-close').forEach(function(closeButton) {
        closeButton.addEventListener('click', function() {
            followingsModal.classList.remove('is-active');
            followersModal.classList.remove('is-active');
        });
    });

    document.querySelectorAll('.modal-background').forEach(function(background) {
        background.addEventListener('click', function() {
            followingsModal.classList.remove('is-active');
            followersModal.classList.remove('is-active');
        });
    });
});
