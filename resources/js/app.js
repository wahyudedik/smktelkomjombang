import './bootstrap';

import Alpine from 'alpinejs';
import Swal from 'sweetalert2';

window.Alpine = Alpine;
window.Swal = Swal;

Alpine.start();

// Service Worker Registration for Offline Support
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js')
            .then((registration) => {
                console.log('SW registered: ', registration);

                // Initialize Push Notifications after SW registration
                if (window.location.pathname.includes('/admin') || window.location.pathname === '/') {
                    initializePushNotifications(registration);
                }

                // Check for updates every hour
                setInterval(() => {
                    registration.update();
                }, 3600000);

                // Handle updates
                registration.addEventListener('updatefound', () => {
                    const newWorker = registration.installing;
                    newWorker.addEventListener('statechange', () => {
                        if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                            // New service worker available, show notification
                            if (window.showConfirm) {
                                window.showConfirm('Update Tersedia', 'Versi baru aplikasi tersedia. Muat ulang halaman?', 'Ya, Muat Ulang', 'Nanti')
                                    .then((result) => {
                                        if (result.isConfirmed) {
                                            window.location.reload();
                                        }
                                    });
                            } else {
                                // Fallback to confirm if showConfirm not available (shouldn't happen in normal usage)
                                if (confirm('Update tersedia! Muat ulang halaman?')) {
                                    window.location.reload();
                                }
                            }
                        }
                    });
                });
            })
            .catch((error) => {
                console.log('SW registration failed: ', error);
            });

        // Handle service worker updates
        let refreshing = false;
        navigator.serviceWorker.addEventListener('controllerchange', () => {
            if (!refreshing) {
                refreshing = true;
                window.location.reload();
            }
        });
    });

    // Listen for online/offline events
    window.addEventListener('online', () => {
        console.log('App is online');
        // Show notification that app is back online
        if (window.showSuccess) {
            window.showSuccess('Koneksi Internet Tersedia', 'Aplikasi kembali online');
        }
    });

    window.addEventListener('offline', () => {
        console.log('App is offline');
        // Show notification that app is offline
        if (window.showAlert) {
            window.showAlert('Mode Offline', 'Aplikasi berjalan dalam mode offline. Beberapa fitur mungkin terbatas.', 'info');
        }
    });
}

// Push Notifications Setup
async function initializePushNotifications(registration) {
    if (!('PushManager' in window)) {
        console.log('Push messaging is not supported');
        return;
    }

    // Check if user is authenticated (check for CSRF token)
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        console.log('User not authenticated, skipping push notification setup');
        return;
    }

    // Check notification permission state BEFORE attempting subscription
    if ('Notification' in window) {
        const permission = Notification.permission;
        
        if (permission === 'denied') {
            // Permission is blocked - user has previously denied and can't be reset programmatically
            console.log('Push notification permission is denied. User must reset permission in browser settings.');
            console.log('To reset: Click the lock/info icon in address bar > Site Settings > Notifications > Allow');
            return; // Exit early, don't try to subscribe
        }

        if (permission === 'default') {
            // Permission hasn't been asked yet - will be prompted when subscribing
            console.log('Push notification permission not yet requested');
        }

        if (permission === 'granted') {
            console.log('Push notification permission already granted');
        }
    }

    try {
        // Get VAPID public key
        const vapidResponse = await fetch('/admin/push/vapid-key', {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            }
        });

        if (!vapidResponse.ok) {
            console.log('VAPID keys not configured');
            return;
        }

        const vapidData = await vapidResponse.json();
        if (!vapidData.success || !vapidData.publicKey) {
            console.log('VAPID public key not available');
            return;
        }

        const publicKey = vapidData.publicKey;

        // Validate key format
        if (!publicKey || typeof publicKey !== 'string' || publicKey.length < 40) {
            console.error('Invalid VAPID public key format. Key length:', publicKey?.length);
            console.error('Please generate proper VAPID keys and add them to .env file.');
            console.error('Visit: https://web-push-codelab.glitch.me/ to generate keys');
            return;
        }

        // Check current subscription
        let subscription = await registration.pushManager.getSubscription();

        // If not subscribed, ask for permission
        if (!subscription) {
            // Double-check permission before attempting subscribe
            if ('Notification' in window && Notification.permission === 'denied') {
                console.log('Skipping subscription attempt - permission is denied');
                return;
            }

            try {
                // Convert VAPID key to Uint8Array
                const applicationServerKey = urlBase64ToUint8Array(publicKey);

                console.log('Subscribing to push notifications with key length:', applicationServerKey.length);

                subscription = await registration.pushManager.subscribe({
                    userVisibleOnly: true,
                    applicationServerKey: applicationServerKey
                });

                console.log('Subscription created:', subscription.endpoint);

                // Send subscription to server
                await sendSubscriptionToServer(subscription);
            } catch (subscribeError) {
                // Handle specific error types gracefully
                if (subscribeError.name === 'NotAllowedError') {
                    // Permission denied - expected if user blocks notification
                    console.log('Push notification permission denied by user');
                    console.log('To enable: Click the lock/info icon in address bar > Site Settings > Notifications > Allow');
                } else if (subscribeError.name === 'InvalidStateError') {
                    // Already subscribed or subscription error
                    console.log('Push subscription state error:', subscribeError.message);
                } else {
                    // Other errors (VAPID key, network, etc.)
                    console.warn('Push subscription error:', subscribeError.name, subscribeError.message);
                }
                // Don't throw - gracefully handle error
                return;
            }
        } else {
            console.log('Already subscribed, updating server...');
            // Update existing subscription on server
            await sendSubscriptionToServer(subscription);
        }

        console.log('Push notification subscription successful');
    } catch (error) {
        // Handle unexpected errors
        if (error.name === 'NotAllowedError') {
            console.log('Push notification permission denied');
        } else {
            console.warn('Push notification setup error:', error.name, error.message);
        }
        // Don't log full error stack for expected permission errors
    }
}

// Send subscription to server
async function sendSubscriptionToServer(subscription) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

    if (!csrfToken) {
        console.log('CSRF token not found');
        return;
    }

    const subscriptionData = {
        endpoint: subscription.endpoint,
        public_key: arrayBufferToBase64(subscription.getKey('p256dh')),
        auth_token: arrayBufferToBase64(subscription.getKey('auth')),
    };

    try {
        const response = await fetch('/admin/push/subscribe', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify(subscriptionData),
        });

        // Check if response is JSON
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            console.error('Non-JSON response from server:', response.status);
            return;
        }

        const data = await response.json();
        if (data.success) {
            console.log('Subscription saved to server');
        } else {
            console.error('Failed to save subscription:', data.message || 'Unknown error');
        }
    } catch (error) {
        console.error('Error sending subscription to server:', error);
    }
}

// Utility: Convert VAPID key from base64url to Uint8Array
// VAPID keys should be in base64url format (RFC 4648)
function urlBase64ToUint8Array(base64String) {
    // Remove any whitespace
    base64String = base64String.trim();

    // Add padding if needed
    const padding = '='.repeat((4 - (base64String.length % 4)) % 4);

    // Convert base64url to base64 (replace - with + and _ with /)
    const base64 = (base64String + padding)
        .replace(/\-/g, '+')
        .replace(/_/g, '/');

    try {
        const rawData = window.atob(base64);
        const outputArray = new Uint8Array(rawData.length);

        for (let i = 0; i < rawData.length; ++i) {
            outputArray[i] = rawData.charCodeAt(i);
        }

        // Validate length - VAPID public key should be 65 bytes (uncompressed) or 32 bytes (compressed)
        // For Web Push, we typically use 65 bytes (0x04 + 32 bytes X + 32 bytes Y)
        if (outputArray.length !== 65 && outputArray.length !== 32) {
            console.warn('VAPID key length is unexpected:', outputArray.length, 'Expected 65 or 32 bytes');
        }

        return outputArray;
    } catch (error) {
        console.error('Error converting VAPID key:', error);
        throw new Error('Invalid VAPID key format');
    }
}

// Utility: Convert ArrayBuffer to base64
function arrayBufferToBase64(buffer) {
    const bytes = new Uint8Array(buffer);
    let binary = '';
    for (let i = 0; i < bytes.byteLength; i++) {
        binary += String.fromCharCode(bytes[i]);
    }
    return window.btoa(binary);
}

// Check notification permission status
window.getNotificationPermissionStatus = function () {
    if (!('Notification' in window)) {
        return {
            supported: false,
            permission: null,
            message: 'Push notifications are not supported in this browser'
        };
    }

    const permission = Notification.permission;
    let message = '';

    switch (permission) {
        case 'granted':
            message = 'Push notifications are enabled';
            break;
        case 'denied':
            message = 'Push notifications are blocked. To enable: Click the lock/info icon in address bar > Site Settings > Notifications > Allow';
            break;
        case 'default':
            message = 'Push notification permission has not been requested yet';
            break;
    }

    return {
        supported: true,
        permission: permission,
        message: message
    };
};

// Request notification permission manually (can be called from UI button)
window.requestNotificationPermission = async function () {
    if (!('Notification' in window)) {
        return {
            success: false,
            message: 'Push notifications are not supported in this browser'
        };
    }

    if (Notification.permission === 'granted') {
        return {
            success: true,
            permission: 'granted',
            message: 'Push notifications are already enabled'
        };
    }

    if (Notification.permission === 'denied') {
        return {
            success: false,
            permission: 'denied',
            message: 'Push notifications are blocked. Please enable them in browser settings: Click the lock/info icon in address bar > Site Settings > Notifications > Allow'
        };
    }

    try {
        const permission = await Notification.requestPermission();
        return {
            success: permission === 'granted',
            permission: permission,
            message: permission === 'granted' 
                ? 'Push notifications enabled successfully' 
                : 'Push notification permission denied'
        };
    } catch (error) {
        return {
            success: false,
            permission: Notification.permission,
            message: 'Error requesting permission: ' + error.message
        };
    }
};

// Unsubscribe function (can be called from UI)
window.unsubscribePushNotifications = async function () {
    if (!('serviceWorker' in navigator) || !('PushManager' in window)) {
        return false;
    }

    try {
        const registration = await navigator.serviceWorker.ready;
        const subscription = await registration.pushManager.getSubscription();

        if (subscription) {
            await subscription.unsubscribe();

            // Notify server
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            if (csrfToken) {
                await fetch('/admin/push/unsubscribe', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ endpoint: subscription.endpoint }),
                });
            }

            console.log('Push notifications unsubscribed');
            return true;
        }
    } catch (error) {
        console.error('Error unsubscribing:', error);
        return false;
    }
    return false;
};

// SweetAlert2 Helper Functions
window.showAlert = function (title, text, icon = 'success') {
    return Swal.fire({
        title: title,
        text: text,
        icon: icon,
        confirmButtonText: 'OK',
        confirmButtonColor: '#3b82f6',
    });
};

window.showConfirm = function (title, text, confirmText = 'Ya', cancelText = 'Batal') {
    return Swal.fire({
        title: title,
        text: text,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3b82f6',
        cancelButtonColor: '#ef4444',
        confirmButtonText: confirmText,
        cancelButtonText: cancelText,
    });
};

window.showSuccess = function (title, text = '') {
    return Swal.fire({
        title: title,
        text: text,
        icon: 'success',
        confirmButtonText: 'OK',
        confirmButtonColor: '#10b981',
        timer: 3000,
        timerProgressBar: true,
    });
};

window.showError = function (title, text = '') {
    return Swal.fire({
        title: title,
        text: text,
        icon: 'error',
        confirmButtonText: 'OK',
        confirmButtonColor: '#ef4444',
    });
};

window.showLoading = function (title = 'Memproses...', text = 'Mohon tunggu') {
    Swal.fire({
        title: title,
        text: text,
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
};

window.closeLoading = function () {
    Swal.close();
};

// Initialize SweetAlert for forms with confirm attribute
document.addEventListener('DOMContentLoaded', function () {
    // Handle all forms with data-confirm attribute
    document.querySelectorAll('form[data-confirm]').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const message = this.dataset.confirm || 'Apakah Anda yakin?';

            showConfirm('Konfirmasi', message, 'Ya', 'Batal').then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    });

    // Replace all onclick with confirm() calls - More secure approach
    document.querySelectorAll('[onclick*="confirm("]').forEach(element => {
        const originalOnclick = element.getAttribute('onclick');
        element.removeAttribute('onclick');

        element.addEventListener('click', function (e) {
            e.preventDefault();

            // Extract confirm message
            const match = originalOnclick.match(/confirm\(['"](.+?)['"]\)/);
            const message = match ? match[1] : 'Apakah Anda yakin?';

            showConfirm('Konfirmasi', message, 'Ya', 'Batal').then((result) => {
                if (result.isConfirmed) {
                    // Check if this is a form submit button or inside a form
                    const form = element.closest('form');
                    if (form) {
                        form.submit();
                    } else {
                        // For links, try to extract href and navigate
                        const href = element.getAttribute('href');
                        if (href && href !== '#') {
                            window.location.href = href;
                        } else {
                            // Try to extract function call from onclick
                            const functionMatch = originalOnclick.match(/(\w+)\s*\(/);
                            if (functionMatch && window[functionMatch[1]]) {
                                // Call the function if it exists globally
                                const args = originalOnclick.match(/\((.*?)\)/);
                                if (args && args[1]) {
                                    const argValues = args[1].split(',').map(arg => {
                                        arg = arg.trim();
                                        // Remove quotes if present
                                        if ((arg.startsWith("'") && arg.endsWith("'")) ||
                                            (arg.startsWith('"') && arg.endsWith('"'))) {
                                            return arg.slice(1, -1);
                                        }
                                        // Try to parse as number
                                        const num = Number(arg);
                                        return isNaN(num) ? arg : num;
                                    });
                                    window[functionMatch[1]](...argValues);
                                } else {
                                    window[functionMatch[1]]();
                                }
                            }
                        }
                    }
                }
            });
        });
    });
});
