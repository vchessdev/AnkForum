// ============================================================
// AnkForum - notifications.js
// Real-time notification polling (AJAX every 5s)
// ============================================================

'use strict';

window.Notifications = {
    count: 0,
    soundEnabled: true,
    interval: null,

    init() {
        if (!window.AnkForum?.user) return;
        this.poll();
        this.interval = setInterval(() => this.poll(), 5000);
    },

    async poll() {
        try {
            const data = await API.get('/api/notifications/unread-count.php');
            const newCount = data.count || 0;

            if (newCount > this.count && this.count > 0) {
                // New notification arrived
                this.playSound();
            }

            this.count = newCount;
            this.updateBadge(newCount);
        } catch (err) {
            // Silently fail
        }
    },

    updateBadge(count) {
        const badges = document.querySelectorAll('.notif-count-badge');
        badges.forEach(badge => {
            if (count > 0) {
                badge.textContent = count > 99 ? '99+' : count;
                badge.style.display = 'inline-flex';
            } else {
                badge.style.display = 'none';
            }
        });
    },

    playSound() {
        if (!this.soundEnabled) return;
        const audio = document.getElementById('notif-sound');
        if (audio) {
            audio.volume = 0.4;
            audio.currentTime = 0;
            audio.play().catch(() => {});
        }
    },

    toggleSound() {
        this.soundEnabled = !this.soundEnabled;
        const btn = document.getElementById('notif-sound-btn');
        if (btn) btn.title = this.soundEnabled ? 'Tắt âm thanh' : 'Bật âm thanh';
        Toast.info(this.soundEnabled ? '🔔 Bật âm thông báo' : '🔕 Tắt âm thông báo');
    },

    stop() {
        if (this.interval) clearInterval(this.interval);
    }
};

document.addEventListener('DOMContentLoaded', () => Notifications.init());
