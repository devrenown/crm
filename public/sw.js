self.addEventListener('notificationclick', function(event) {
    event.notification.close();

    const targetUrl = event.notification.data.url;

    if (!targetUrl) return;

    event.waitUntil(
        clients.matchAll({
            type: 'window',
            includeUncontrolled: true
        }).then(function(clientList) {
            
            for (const client of clientList) {
                const urlObj = new URL(client.url);
                if (urlObj.pathname.includes('/apps/chat')) {
                    
                    if ('focus' in client) client.focus();
                    
                    if (client.url !== targetUrl && 'navigate' in client) {
                        return client.navigate(targetUrl);
                    }
                    return;
                }
            }

            if (clients.openWindow) {
                return clients.openWindow(targetUrl);
            }
        })
    );
});