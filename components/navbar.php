<?php
// components/navbar.php
$user = currentUser();
?>
<nav class="glass-navbar fixed top-0 left-0 right-0 h-16 z-30 flex items-center px-4 gap-4">

    <!-- Logo -->
    <a href="/" class="flex items-center gap-2.5 flex-shrink-0 mr-2">
        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-brand-500 to-brand-700 flex items-center justify-center shadow-md">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
            </svg>
        </div>
        <span class="text-lg font-black gradient-text font-display hidden sm:block">AnkForum</span>
    </a>

    <!-- Search bar -->
    <div class="flex-1 max-w-md relative">
        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 pointer-events-none">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        </span>
        <input type="text" id="search-input" placeholder="Tìm kiếm..."
            class="input-field pl-9 pr-4 h-10 text-sm"
            style="border-radius:999px; padding-top:0; padding-bottom:0;"
            autocomplete="off">
        <!-- Search results dropdown -->
        <div id="search-results" class="dropdown-menu absolute top-full left-0 right-0 mt-2 hidden" style="max-height:360px;overflow-y:auto;"></div>
    </div>

    <!-- Right actions -->
    <div class="flex items-center gap-1 ml-auto flex-shrink-0">

        <!-- Dark mode -->
        <button onclick="DarkMode.toggle()" id="dark-mode-btn" title="Chế độ tối"
            class="btn-ghost p-2.5 rounded-xl">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
        </button>

        <?php if ($user): ?>

        <!-- Notifications -->
        <a href="/?page=notifications" class="btn-ghost p-2.5 rounded-xl relative">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
            <span class="notif-count-badge notif-badge absolute -top-0.5 -right-0.5 hidden" style="font-size:9px;min-width:16px;height:16px;"></span>
        </a>

        <!-- Notification sound toggle -->
        <button onclick="Notifications.toggleSound()" id="notif-sound-btn" title="Tắt âm thanh" class="btn-ghost p-2.5 rounded-xl hidden sm:flex">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14M15.54 8.46a5 5 0 0 1 0 7.07"/></svg>
        </button>

        <!-- User menu -->
        <div class="relative">
            <button data-dropdown="user-menu" class="flex items-center gap-2 pl-1 pr-2 py-1 rounded-xl hover:bg-white/5 transition-colors">
                <div class="relative">
                    <img src="<?php echo avatarUrl($user['avatar']); ?>"
                        alt="<?php echo sanitize($user['username']); ?>"
                        class="avatar w-8 h-8">
                    <?php
                        require_once __DIR__ . '/../components/livestream-indicator.php';
                        $userStream = getUserLivestream($user['id']);
                        if ($userStream):
                    ?>
                    <span class="absolute -top-1 -right-1 px-1.5 py-0.5 bg-red-600 text-white text-xs font-bold rounded animate-pulse"
                          style="font-size:8px;min-width:fit-content;">LIVE</span>
                    <?php else: ?>
                    <span class="online-dot" style="width:8px;height:8px;bottom:-1px;right:-1px;"></span>
                    <?php endif; ?>
                </div>
                <div class="hidden sm:block text-left">
                    <p class="text-xs font-semibold text-slate-200 leading-tight"><?php echo sanitize($user['username']); ?></p>
                    <?php $lvlInfo = getLevelInfo($user['level']); ?>
                    <p class="text-xs" style="color:<?php echo $lvlInfo['color']; ?>"><?php echo $lvlInfo['icon']; ?> Lv.<?php echo $user['level']; ?></p>
                </div>
                <svg class="hidden sm:block w-3 h-3 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
            </button>

            <div id="user-menu" class="dropdown-menu absolute right-0 top-full mt-2 hidden" style="min-width:200px;">
                <div class="px-3 py-2 border-b border-white/5 mb-1">
                    <p class="text-xs font-semibold text-white"><?php echo sanitize($user['username']); ?></p>
                    <p class="text-xs text-slate-500"><?php echo sanitize($user['email']); ?></p>
                </div>
                <a href="/?page=profile&u=<?php echo urlencode($user['username']); ?>" class="dropdown-item">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    Trang cá nhân
                </a>
                <a href="/?page=settings" class="dropdown-item">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                    Cài đặt
                </a>
                <div class="border-t border-white/5 mt-1 pt-1">
                    <button onclick="doLogout()" class="dropdown-item danger w-full">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                        Đăng xuất
                    </button>
                </div>
            </div>
        </div>

        <?php else: ?>

        <a href="/?page=login" class="btn-ghost text-sm px-4 h-9">Đăng nhập</a>
        <a href="/?page=register" class="btn-primary text-sm px-4 h-9">Đăng ký</a>

        <?php endif; ?>
    </div>
</nav>

<script>
// Search
const searchInput = document.getElementById('search-input');
const searchResults = document.getElementById('search-results');

let searchTimeout;
searchInput?.addEventListener('input', function() {
    clearTimeout(searchTimeout);
    const q = this.value.trim();
    if (q.length < 2) { searchResults.classList.add('hidden'); return; }
    searchTimeout = setTimeout(async () => {
        try {
            const data = await API.get('/api/search.php?q=' + encodeURIComponent(q));
            renderSearchResults(data);
        } catch(e) {}
    }, 300);
});

function renderSearchResults(data) {
    if (!data.users?.length && !data.posts?.length) {
        searchResults.innerHTML = '<p class="text-xs text-slate-500 text-center py-3">Không tìm thấy kết quả</p>';
    } else {
        let html = '';
        if (data.users?.length) {
            html += '<p class="text-xs text-slate-500 px-3 py-1 font-semibold uppercase tracking-wider">Người dùng</p>';
            data.users.forEach(u => {
                html += `<a href="/?page=profile&u=${encodeURIComponent(u.username)}" class="dropdown-item">
                    <img src="${u.avatar_url}" class="w-7 h-7 rounded-full object-cover">
                    <div><p class="text-sm font-medium text-white">${u.username}</p></div>
                </a>`;
            });
        }
        if (data.posts?.length) {
            html += '<p class="text-xs text-slate-500 px-3 py-2 font-semibold uppercase tracking-wider border-t border-white/5 mt-1">Bài viết</p>';
            data.posts.forEach(p => {
                html += `<a href="/?page=post&id=${p.id}" class="dropdown-item truncate">${p.content_preview}</a>`;
            });
        }
        searchResults.innerHTML = html;
    }
    searchResults.classList.remove('hidden');
}

document.addEventListener('click', (e) => {
    if (!e.target.closest('#search-input') && !e.target.closest('#search-results')) {
        searchResults.classList.add('hidden');
    }
});

async function doLogout() {
    try {
        await API.post('/api/auth/logout.php', {});
    } catch(e) {}
    location.href = '/?page=login';
}
</script>
