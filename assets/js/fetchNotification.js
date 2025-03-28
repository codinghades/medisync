document.addEventListener('DOMContentLoaded', function() {
    fetchNotifications();
    setInterval(fetchNotifications, 10000);
});

function fetchNotifications() {
    fetch('../process/getNotification.php')
        .then(response => response.json())
        .then(data => {
            displayNotifications(data);
        })
        .catch(error => console.error('Error fetching notifications:', error));
}

function displayNotifications(notifications) {
    const container = document.querySelector('.notificationsList');
    container.innerHTML = '<p>Notifications</p>';

    if (notifications.length === 0) {
        container.innerHTML += '<p class="nothing">No notifications available.</p>';
        return;
    }

    notifications.forEach(notification => {
        const notificationElement = `
            <div class="notification">
                <dl>
                    <dt>
                        <span class="name">${notification.title}</span>
                        <span class="date">${new Date(notification.created_at).toLocaleDateString()}</span>
                    </dt>
                    <dd>
                        <span class="info">${notification.message}</span>
                    </dd>
                </dl>
            </div>`;
        container.innerHTML += notificationElement;
    });
}
