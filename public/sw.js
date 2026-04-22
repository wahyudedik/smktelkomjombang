// Service Worker for Offline Support
const CACHE_NAME = 'ig-to-web-v1';
const STATIC_CACHE_NAME = 'ig-to-web-static-v1';
const DYNAMIC_CACHE_NAME = 'ig-to-web-dynamic-v1';

// Assets to cache on install
const STATIC_ASSETS = [
    '/',
    '/assets/css/all-fontawesome.min.css',
    '/assets/css/style.css',
    '/assets/js/bootstrap.bundle.min.js',
    '/assets/js/jquery-3.7.1.min.js',
    '/favicon.ico',
];

// Cache strategy: Cache First for static assets
async function cacheFirst(request) {
    const cachedResponse = await caches.match(request);
    if (cachedResponse) {
        return cachedResponse;
    }

    try {
        const networkResponse = await fetch(request);
        if (networkResponse.ok) {
            const cache = await caches.open(DYNAMIC_CACHE_NAME);
            cache.put(request, networkResponse.clone());
        }
        return networkResponse;
    } catch (error) {
        // Return offline page if available
        const offlinePage = await caches.match('/offline');
        if (offlinePage) {
            return offlinePage;
        }
        throw error;
    }
}

// Cache strategy: Network First with fallback
async function networkFirst(request) {
    try {
        const networkResponse = await fetch(request);
        if (networkResponse.ok) {
            const cache = await caches.open(DYNAMIC_CACHE_NAME);
            cache.put(request, networkResponse.clone());
        }
        return networkResponse;
    } catch (error) {
        const cachedResponse = await caches.match(request);
        if (cachedResponse) {
            return cachedResponse;
        }
        // Return offline page for navigation requests
        if (request.mode === 'navigate') {
            const offlinePage = await caches.match('/offline');
            if (offlinePage) {
                return offlinePage;
            }
        }
        throw error;
    }
}

// Install event - Cache static assets
self.addEventListener('install', (event) => {
    console.log('[SW] Installing service worker...');
    event.waitUntil(
        caches.open(STATIC_CACHE_NAME)
            .then((cache) => {
                console.log('[SW] Caching static assets');
                return cache.addAll(STATIC_ASSETS);
            })
            .then(() => self.skipWaiting())
    );
});

// Activate event - Clean up old caches
self.addEventListener('activate', (event) => {
    console.log('[SW] Activating service worker...');
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames
                    .filter((cacheName) => {
                        return cacheName !== STATIC_CACHE_NAME &&
                            cacheName !== DYNAMIC_CACHE_NAME;
                    })
                    .map((cacheName) => {
                        console.log('[SW] Deleting old cache:', cacheName);
                        return caches.delete(cacheName);
                    })
            );
        })
            .then(() => self.clients.claim())
    );
});

// Fetch event - Handle requests
self.addEventListener('fetch', (event) => {
    const { request } = event;
    const url = new URL(request.url);

    // Skip non-GET requests
    if (request.method !== 'GET') {
        return;
    }

    // Skip external requests (CDN, API from other domains)
    if (url.origin !== location.origin && !url.href.startsWith('http://localhost')) {
        return;
    }

    // Skip admin/auth routes (these should always be online)
    if (url.pathname.startsWith('/admin') ||
        url.pathname.startsWith('/login') ||
        url.pathname.startsWith('/register')) {
        event.respondWith(fetch(request));
        return;
    }

    // Cache strategy based on resource type
    if (url.pathname.match(/\.(css|js|woff|woff2|ttf|png|jpg|jpeg|gif|svg|ico)$/)) {
        // Static assets: Cache First
        event.respondWith(cacheFirst(request));
    } else if (url.pathname.startsWith('/api/')) {
        // API requests: Network First
        event.respondWith(networkFirst(request));
    } else {
        // HTML pages: Network First with fallback
        event.respondWith(networkFirst(request));
    }
});

// Message event - Handle messages from main thread
self.addEventListener('message', (event) => {
    if (event.data && event.data.type === 'SKIP_WAITING') {
        self.skipWaiting();
    }

    if (event.data && event.data.type === 'CACHE_URLS') {
        event.waitUntil(
            caches.open(DYNAMIC_CACHE_NAME).then((cache) => {
                return cache.addAll(event.data.urls);
            })
        );
    }
});

// Push event - Handle incoming push notifications
self.addEventListener('push', (event) => {
    console.log('[SW] Push notification received:', event);

    let notificationData = {
        title: 'IG to Web',
        body: 'Anda memiliki notifikasi baru',
        icon: '/assets/img/logo/favicon.png',
        badge: '/assets/img/logo/favicon.png',
        tag: 'notification',
        data: {
            url: '/admin/notifications',
        },
    };

    // Parse push data if available
    if (event.data) {
        try {
            const data = event.data.json();
            notificationData = {
                title: data.title || notificationData.title,
                body: data.body || data.message || notificationData.body,
                icon: data.icon || notificationData.icon,
                badge: data.badge || notificationData.badge,
                tag: data.tag || notificationData.tag,
                data: {
                    url: data.url || data.data?.url || notificationData.data.url,
                    ...data.data,
                },
                requireInteraction: data.requireInteraction || false,
                silent: data.silent || false,
            };
        } catch (e) {
            console.log('[SW] Error parsing push data:', e);
            // Use default notification data
        }
    }

    event.waitUntil(
        self.registration.showNotification(notificationData.title, {
            body: notificationData.body,
            icon: notificationData.icon,
            badge: notificationData.badge,
            tag: notificationData.tag,
            data: notificationData.data,
            requireInteraction: notificationData.requireInteraction,
            silent: notificationData.silent,
            vibrate: [200, 100, 200],
            actions: [
                {
                    action: 'open',
                    title: 'Buka',
                },
                {
                    action: 'close',
                    title: 'Tutup',
                },
            ],
        })
    );
});

// Notification click event - Handle when user clicks on notification
self.addEventListener('notificationclick', (event) => {
    console.log('[SW] Notification clicked:', event);

    event.notification.close();

    const action = event.action;
    const url = event.notification.data?.url || '/';

    if (action === 'close') {
        return;
    }

    // Open or focus the window
    event.waitUntil(
        clients.matchAll({
            type: 'window',
            includeUncontrolled: true,
        }).then((clientList) => {
            // Check if there's already a window/tab open
            for (let i = 0; i < clientList.length; i++) {
                const client = clientList[i];
                if (client.url === url && 'focus' in client) {
                    return client.focus();
                }
            }

            // If no window/tab is open, open a new one
            if (clients.openWindow) {
                return clients.openWindow(url);
            }
        })
    );
});

// Notification close event
self.addEventListener('notificationclose', (event) => {
    console.log('[SW] Notification closed:', event);
});

console.log('[SW] Service worker loaded with push notification support');

