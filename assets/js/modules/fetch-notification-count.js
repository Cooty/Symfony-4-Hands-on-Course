export default function fetchNotificationCount() {
    fetch(
        window._config.notificationUrl,
        {'credentials': 'include'}
    ).then((response)=> {
        response.json()
            .then((data)=> {
                document.getElementById('js-notification-count').innerHTML = data.count;
                setTimeout(fetchNotificationCount, 5000);
            });
    }).catch((reason)=> {
        console.warn(reason);
    });
}