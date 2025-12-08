/**
 * Notification System
 * Polling setiap beberapa detik untuk cek pesan baru
 */

const NOTIFICATION_INTERVAL = 5000; // 5 detik
const API_URL = 'chat-api-service/controller_notification.php?action=checkNotification';

function initNotification() {
    // Cari elemen badge di header (User & Dokter)
    // Kita asumsikan ada elemen dengan ID 'notification-badge' atau class 'notification-badge'
    // di dalam menu Chat.
    
    updateBadge(); // Cek langsung saat load
    
    setInterval(updateBadge, NOTIFICATION_INTERVAL);
}

async function updateBadge() {
    try {
        const response = await fetch(API_URL);
        const data = await response.json();

        if (data.success) {
            renderBadge(data.unread);
        }
    } catch (error) {
        // Silent error (jangan ganggu user jika polling gagal sesekali)
        console.warn('Notification polling failed:', error);
    }
}

function renderBadge(count) {
    // 1. Badge untuk Header User (biasanya di icon Chat/Konsultasi)
    const badgeElements = document.querySelectorAll('.notification-badge');
    
    badgeElements.forEach(badge => {
        if (count > 0) {
            badge.textContent = count > 99 ? '99+' : count;
            badge.classList.remove('hidden');
            badge.classList.add('flex'); // Pastikan display flex/block
            
            // Animasi kecil jika angka berubah (opsional)
            badge.classList.add('scale-110');
            setTimeout(() => badge.classList.remove('scale-110'), 200);
            
        } else {
            badge.classList.add('hidden');
            badge.classList.remove('flex');
        }
    });

    // Update favicon badge (opsional, browser modern support)
    if (navigator.setAppBadge) {
        if (count > 0) navigator.setAppBadge(count);
        else navigator.clearAppBadge();
    }
}

// Start notification system on load
document.addEventListener('DOMContentLoaded', initNotification);
