// ============================================================
// AnkForum - app.js
// Core JS utilities
// ============================================================

'use strict';

// ── Toast notification system ─────────────────────────────────

window.Toast = {
    show(message, type = 'info', duration = 4000) {
        const container = document.getElementById('toast-container');
        if (!container) return;

        const icons = {
            success: `<svg class="toast-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg>`,
            error:   `<svg class="toast-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>`,
            info:    `<svg class="toast-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>`,
        };

        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        toast.innerHTML = `${icons[type] || icons.info}<span>${message}</span>`;
        container.appendChild(toast);

        setTimeout(() => {
            toast.classList.add('removing');
            setTimeout(() => toast.remove(), 300);
        }, duration);
    },

    success(msg) { this.show(msg, 'success'); },
    error(msg)   { this.show(msg, 'error');   },
    info(msg)    { this.show(msg, 'info');    },
};

// ── Modal system ──────────────────────────────────────────────

window.Modal = {
    open(id) {
        const el = document.getElementById(id);
        if (el) { el.style.display = 'flex'; document.body.style.overflow = 'hidden'; }
    },
    close(id) {
        const el = document.getElementById(id);
        if (el) { el.style.display = 'none'; document.body.style.overflow = ''; }
    },
    create({ title, content, actions = '' }) {
        const id = 'modal-' + Date.now();
        const overlay = document.createElement('div');
        overlay.id = id;
        overlay.className = 'modal-overlay';
        overlay.innerHTML = `
            <div class="modal-box" onclick="event.stopPropagation()">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-lg font-bold text-white">${title}</h3>
                    <button onclick="Modal.close('${id}')" class="btn-ghost p-2 rounded-lg">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    </button>
                </div>
                <div class="text-sm text-slate-300">${content}</div>
                ${actions ? `<div class="flex justify-end gap-3 mt-6">${actions}</div>` : ''}
            </div>`;
        overlay.addEventListener('click', () => { overlay.remove(); document.body.style.overflow = ''; });
        document.body.appendChild(overlay);
        return id;
    }
};

// ── Dropdown toggle ───────────────────────────────────────────

document.addEventListener('click', (e) => {
    const trigger = e.target.closest('[data-dropdown]');
    if (trigger) {
        e.stopPropagation();
        const targetId = trigger.dataset.dropdown;
        const menu = document.getElementById(targetId);
        if (!menu) return;
        const isOpen = menu.style.display !== 'none';
        closeAllDropdowns();
        if (!isOpen) menu.style.display = 'block';
        return;
    }
    closeAllDropdowns();
});

function closeAllDropdowns() {
    document.querySelectorAll('.dropdown-menu').forEach(m => m.style.display = 'none');
}

// ── Lightbox ──────────────────────────────────────────────────

window.Lightbox = {
    open(src) {
        let lb = document.getElementById('lightbox');
        if (!lb) {
            lb = document.createElement('div');
            lb.id = 'lightbox';
            lb.innerHTML = `<img src="" alt="Preview">`;
            lb.addEventListener('click', () => lb.classList.remove('active'));
            document.body.appendChild(lb);
        }
        lb.querySelector('img').src = src;
        lb.classList.add('active');
    }
};

document.addEventListener('click', (e) => {
    const img = e.target.closest('.post-media img');
    if (img) Lightbox.open(img.src);
});

// ── Dark mode ─────────────────────────────────────────────────

window.DarkMode = {
    init() {
        const saved = localStorage.getItem('theme');
        if (saved === 'light') document.documentElement.classList.remove('dark');
        else document.documentElement.classList.add('dark');
    },
    toggle() {
        const isDark = document.documentElement.classList.toggle('dark');
        localStorage.setItem('theme', isDark ? 'dark' : 'light');
        const btn = document.getElementById('dark-mode-btn');
        if (btn) btn.title = isDark ? 'Chế độ sáng' : 'Chế độ tối';
    }
};
DarkMode.init();

// ── Infinite scroll ───────────────────────────────────────────

window.InfiniteScroll = {
    callbacks: [],
    observe() {
        const sentinel = document.getElementById('scroll-sentinel');
        if (!sentinel) return;
        const observer = new IntersectionObserver((entries) => {
            if (entries[0].isIntersecting) {
                this.callbacks.forEach(cb => cb());
            }
        }, { rootMargin: '200px' });
        observer.observe(sentinel);
    },
    onLoad(cb) { this.callbacks.push(cb); }
};

// ── Character counter ─────────────────────────────────────────

document.addEventListener('input', (e) => {
    const textarea = e.target.closest('[data-max-length]');
    if (!textarea) return;
    const max = parseInt(textarea.dataset.maxLength);
    const count = textarea.value.length;
    const counter = document.getElementById(textarea.dataset.counterId);
    if (counter) {
        counter.textContent = max - count;
        counter.className = count > max * 0.9
            ? 'text-xs text-red-400'
            : 'text-xs text-muted';
    }
});

// ── Auto-resize textarea ───────────────────────────────────────

document.addEventListener('input', (e) => {
    if (e.target.classList.contains('auto-resize')) {
        e.target.style.height = 'auto';
        e.target.style.height = e.target.scrollHeight + 'px';
    }
});

// ── Lazy load images ──────────────────────────────────────────

if ('IntersectionObserver' in window) {
    const lazyObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                if (img.dataset.src) { img.src = img.dataset.src; delete img.dataset.src; }
                lazyObserver.unobserve(img);
            }
        });
    });
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('img[data-src]').forEach(img => lazyObserver.observe(img));
    });
}

// ── Format numbers ────────────────────────────────────────────

window.formatNum = (n) => {
    if (n >= 1e6) return (n / 1e6).toFixed(1) + 'M';
    if (n >= 1e3) return (n / 1e3).toFixed(1) + 'K';
    return n.toString();
};

// ── Timeago (client side) ─────────────────────────────────────

window.timeAgo = (dateStr) => {
    const now  = new Date();
    const then = new Date(dateStr);
    const diff = Math.floor((now - then) / 1000);
    if (diff < 60)    return 'Vừa xong';
    if (diff < 3600)  return Math.floor(diff / 60) + ' phút trước';
    if (diff < 86400) return Math.floor(diff / 3600) + ' giờ trước';
    if (diff < 2592000) return Math.floor(diff / 86400) + ' ngày trước';
    if (diff < 31536000) return Math.floor(diff / 2592000) + ' tháng trước';
    return Math.floor(diff / 31536000) + ' năm trước';
};

// ── File size formatter ───────────────────────────────────────

window.formatBytes = (bytes) => {
    if (bytes === 0) return '0 Bytes';
    const k = 1024, sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
};

// ── Debounce ──────────────────────────────────────────────────

window.debounce = (fn, delay = 300) => {
    let t;
    return (...args) => { clearTimeout(t); t = setTimeout(() => fn(...args), delay); };
};

// ── Active page highlight in sidebar ─────────────────────────

document.addEventListener('DOMContentLoaded', () => {
    const page = new URLSearchParams(location.search).get('page') || 'home';
    document.querySelectorAll('.sidebar-link').forEach(link => {
        const href = link.getAttribute('href') || '';
        const linkPage = new URLSearchParams(href.split('?')[1] || '').get('page') || 'home';
        if (linkPage === page) link.classList.add('active');
    });
});
