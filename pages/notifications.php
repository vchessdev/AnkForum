<?php
// pages/notifications.php
$user = currentUser();
?>
<div class="animate-fade-in">

    <div class="flex items-center justify-between mb-5">
        <h1 class="text-xl font-black text-white flex items-center gap-2">
            <div class="w-9 h-9 rounded-xl bg-brand-500/20 flex items-center justify-center">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#818cf8" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
            </div>
            Thông báo
        </h1>
        <button onclick="markAllRead()" class="btn-ghost text-xs">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
            Đánh dấu tất cả đã đọc
        </button>
    </div>

    <!-- Notifications list -->
    <div id="notif-list" class="space-y-2">
        <!-- Loading skeleton -->
        <?php for ($i=0; $i<5; $i++): ?>
        <div class="glass-card p-4 flex gap-3 skeleton-notif">
            <div class="skeleton w-11 h-11 rounded-full flex-shrink-0"></div>
            <div class="flex-1 space-y-2">
                <div class="skeleton h-3 w-3/4 rounded"></div>
                <div class="skeleton h-2 w-1/3 rounded"></div>
            </div>
        </div>
        <?php endfor; ?>
    </div>

    <!-- Load more -->
    <div id="notif-sentinel" class="py-6 text-center">
        <div id="notif-loader" class="hidden">
            <svg class="animate-spin w-5 h-5 mx-auto text-brand-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v4m0 12v4M4.93 4.93l2.83 2.83m8.48 8.48l2.83 2.83M2 12h4m12 0h4M4.93 19.07l2.83-2.83m8.48-8.48l2.83-2.83"/></svg>
        </div>
        <p id="notif-end" class="hidden text-xs text-slate-600">Đã xem hết thông báo</p>
    </div>
</div>

<script>
let notifPage = 1;
let notifMore = true;
let notifLoading = false;

const NOTIF_ICONS = {
    follow: { icon: `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg>`, color: '#22d3ee', bg: 'rgba(34,211,238,0.12)' },
    like:   { icon: `<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" stroke="none"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>`, color: '#f87171', bg: 'rgba(248,113,113,0.12)' },
    comment:{ icon: `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>`, color: '#818cf8', bg: 'rgba(129,140,248,0.12)' },
    reply:  { icon: `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 17 4 12 9 7"/><path d="M20 18v-2a4 4 0 0 0-4-4H4"/></svg>`, color: '#a78bfa', bg: 'rgba(167,139,250,0.12)' },
    new_post:{ icon: `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>`, color: '#34d399', bg: 'rgba(52,211,153,0.12)' },
};

function getNotifText(n) {
    const d = n.data || {};
    const u = `<a href="/?page=profile&u=${encodeURIComponent(d.from_username || '')}" class="font-bold text-white hover:text-brand-300 transition-colors">${d.from_username || 'Ai đó'}</a>`;
    switch(n.type) {
        case 'follow':   return `${u} đã bắt đầu theo dõi bạn`;
        case 'like':     return `${u} đã thích bài viết của bạn${d.post_preview ? `: "${d.post_preview}"` : ''}`;
        case 'comment':  return `${u} đã bình luận về bài viết của bạn${d.preview ? `: "${d.preview}"` : ''}`;
        case 'reply':    return `${u} đã trả lời bình luận của bạn${d.preview ? `: "${d.preview}"` : ''}`;
        case 'new_post': return `${u} vừa đăng bài mới${d.post_preview ? `: "${d.post_preview}"` : ''}`;
        default:         return `${u} đã tương tác với bạn`;
    }
}

function getNotifLink(n) {
    const d = n.data || {};
    switch(n.type) {
        case 'follow':   return `/?page=profile&u=${encodeURIComponent(d.from_username || '')}`;
        case 'like':
        case 'comment':
        case 'reply':
        case 'new_post': return d.post_id ? `/?page=post&id=${d.post_id}` : '#';
        default:         return '#';
    }
}

function renderNotification(n) {
    const info = NOTIF_ICONS[n.type] || NOTIF_ICONS.like;
    const d    = n.data || {};
    const link = getNotifLink(n);
    const avatar = d.from_avatar ? (d.from_avatar === 'default.png' ? 'assets/images/default-avatar.svg' : d.from_avatar) : 'assets/images/default-avatar.svg';

    return `
    <a href="${link}" class="block group">
        <div class="glass-card p-4 flex items-start gap-3 transition-all ${!n.read ? 'border-brand-500/25 bg-brand-500/5' : ''} hover:border-brand-500/30">
            <div class="relative flex-shrink-0">
                <img src="${avatar}" class="avatar w-11 h-11">
                <div class="absolute -bottom-1 -right-1 w-6 h-6 rounded-full flex items-center justify-center"
                    style="background:${info.bg};color:${info.color};border:1.5px solid ${info.color}40;">
                    ${info.icon}
                </div>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm text-slate-300 leading-relaxed">${getNotifText(n)}</p>
                <p class="text-xs text-slate-500 mt-1">${n.time_ago}</p>
            </div>
            ${!n.read ? '<div class="w-2 h-2 bg-brand-400 rounded-full flex-shrink-0 mt-2 animate-pulse"></div>' : ''}
        </div>
    </a>`;
}

async function loadNotifications(reset = false) {
    if (notifLoading || !notifMore) return;
    notifLoading = true;
    if (!reset) document.getElementById('notif-loader').classList.remove('hidden');

    try {
        const data = await API.get('/api/notifications/list.php?page=' + notifPage);
        document.querySelectorAll('.skeleton-notif').forEach(el => el.remove());

        const list = document.getElementById('notif-list');
        if (data.notifications.length === 0 && notifPage === 1) {
            list.innerHTML = `
                <div class="text-center py-16 animate-fade-in">
                    <div class="text-5xl mb-4">🔕</div>
                    <p class="text-slate-400 font-medium">Chưa có thông báo nào</p>
                    <p class="text-slate-600 text-sm mt-1">Tương tác với cộng đồng để nhận thông báo</p>
                </div>`;
        } else {
            data.notifications.forEach(n => list.insertAdjacentHTML('beforeend', renderNotification(n)));
        }

        // Reset badge count after viewing
        document.querySelectorAll('.notif-count-badge').forEach(b => b.style.display = 'none');
        if (window.Notifications) window.Notifications.count = 0;

        notifMore = data.has_more;
        notifPage++;
        if (!notifMore) document.getElementById('notif-end').classList.remove('hidden');
    } catch(e) {
        Toast.error('Không thể tải thông báo');
    } finally {
        notifLoading = false;
        document.getElementById('notif-loader').classList.add('hidden');
    }
}

async function markAllRead() {
    try {
        await API.post('/api/notifications/mark-read.php', {});
        document.querySelectorAll('.animate-pulse.w-2').forEach(el => el.remove());
        document.querySelectorAll('.border-brand-500\\/25').forEach(el => {
            el.classList.remove('border-brand-500/25', 'bg-brand-500/5');
        });
        Toast.success('Đã đánh dấu tất cả đã đọc');
    } catch(e) {}
}

// Infinite scroll
const notifObserver = new IntersectionObserver(entries => {
    if (entries[0].isIntersecting && notifMore && !notifLoading) loadNotifications();
}, { rootMargin: '300px' });

document.addEventListener('DOMContentLoaded', () => {
    loadNotifications();
    const sentinel = document.getElementById('notif-sentinel');
    if (sentinel) notifObserver.observe(sentinel);
});
</script>
