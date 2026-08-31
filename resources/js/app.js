import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Global notification polling store
Alpine.store('notifikasi', {
    items: [],
    unreadCount: 0,
    isOpen: false,
    loading: false,

    async fetch() {
        this.loading = true;
        try {
            const response = await fetch('/api/notifikasi', {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            });
            const data = await response.json();
            this.items = data.notifikasis;
            this.unreadCount = data.unread_count;
        } catch (e) {
            console.error('Failed to fetch notifications', e);
        }
        this.loading = false;
    },

    async markAsRead(id) {
        try {
            await fetch(`/api/notifikasi/${id}/read`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            this.fetch();
        } catch (e) {
            console.error('Failed to mark notification as read', e);
        }
    },

    async markAllAsRead() {
        try {
            await fetch('/api/notifikasi/read-all', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            this.fetch();
        } catch (e) {
            console.error('Failed to mark all notifications as read', e);
        }
    },

    toggle() {
        this.isOpen = !this.isOpen;
        if (this.isOpen) {
            this.fetch();
        }
    }
});

Alpine.start();

// Poll notifications every 30 seconds
setInterval(() => {
    Alpine.store('notifikasi').fetch();
}, 30000);

// Initial fetch
document.addEventListener('DOMContentLoaded', () => {
    Alpine.store('notifikasi').fetch();
});
