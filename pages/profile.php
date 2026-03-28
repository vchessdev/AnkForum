<?php
// pages/profile.php
$currentUser = currentUser();
$username    = $_GET['u'] ?? ($currentUser['username'] ?? '');
if (empty($username)) { redirect('/'); }
?>

<div id="profile-page" class="animate-fade-in">

    <!-- Skeleton while loading -->
    <div id="profile-skeleton">
        <!-- Banner skeleton -->
        <div class="skeleton rounded-2xl mb-4" style="height:180px;"></div>
        <!-- Info skeleton -->
        <div class="glass-card p-6 mb-4">
            <div class="flex gap-4">
                <div class="skeleton w-20 h-20 rounded-full flex-shrink-0 -mt-10"></div>
                <div class="flex-1 pt-2 space-y-2">
                    <div class="skeleton h-4 w-40 rounded"></div>
                    <div class="skeleton h-3 w-60 rounded"></div>
                    <div class="skeleton h-3 w-32 rounded"></div>
                </div>
            </div>
        </div>
        <!-- Posts skeleton -->
        <div class="space-y-4">
            <?php for ($i=0;$i<3;$i++): ?>
            <div class="post-card p-5">
                <div class="skeleton h-3 w-full rounded mb-2"></div>
                <div class="skeleton h-3 w-4/5 rounded mb-2"></div>
                <div class="skeleton h-3 w-3/5 rounded"></div>
            </div>
            <?php endfor; ?>
        </div>
    </div>

    <!-- Loaded content (hidden until API returns) -->
    <div id="profile-content" class="hidden">

        <!-- Banner -->
        <div id="profile-banner-wrap" class="relative rounded-2xl overflow-hidden mb-4 group" style="height:200px;background:#1e1e2e;">
            <img id="profile-banner" src="" alt="Banner"
                class="w-full h-full object-cover opacity-70">
            <?php if ($currentUser && $currentUser['username'] === $username): ?>
            <label for="banner-input" class="absolute inset-0 flex items-center justify-center bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                <div class="flex items-center gap-2 text-white text-sm font-semibold bg-black/50 px-4 py-2 rounded-xl">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                    Đổi ảnh bìa
                </div>
            </label>
            <input type="file" id="banner-input" accept="image/*" class="hidden" onchange="uploadBanner(this)">
            <?php endif; ?>
        </div>

        <!-- Profile info card -->
        <div class="glass-card p-6 mb-4 relative">
            <!-- Avatar -->
            <div class="flex flex-col sm:flex-row sm:items-end gap-4 -mt-16 mb-4">
                <div class="relative group w-24 flex-shrink-0">
                    <img id="profile-avatar" src="" alt="Avatar"
                        class="w-24 h-24 rounded-2xl object-cover border-4 border-surface-900 shadow-xl">
                    <?php if ($currentUser && $currentUser['username'] === $username): ?>
                    <label for="avatar-input" class="absolute inset-0 flex items-center justify-center bg-black/60 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                    </label>
                    <input type="file" id="avatar-input" accept="image/*" class="hidden" onchange="uploadAvatar(this)">
                    <?php endif; ?>
                </div>

                <!-- Name + actions -->
                <div class="flex-1 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h1 id="profile-display-name" class="text-xl font-black text-white"></h1>
                        <p id="profile-username-text" class="text-sm text-slate-400"></p>
                        <div id="profile-level-badge" class="mt-1.5"></div>
                    </div>
                    <div id="profile-actions" class="flex gap-2"></div>
                </div>
            </div>

            <!-- Bio -->
            <div id="profile-bio-section" class="mb-4">
                <p id="profile-bio" class="text-sm text-slate-300 leading-relaxed"></p>
                <div id="bio-edit-form" class="hidden">
                    <textarea id="bio-textarea" rows="3" maxlength="300"
                        class="input-field text-sm mt-2 w-full" placeholder="Viết gì đó về bạn..."></textarea>
                    <div class="flex gap-2 mt-2">
                        <button onclick="saveBio()" class="btn-primary text-xs px-3 py-1.5">Lưu</button>
                        <button onclick="cancelBio()" class="btn-ghost text-xs px-3 py-1.5">Hủy</button>
                    </div>
                </div>
            </div>

            <!-- Stats row -->
            <div class="grid grid-cols-4 gap-2 pt-4 border-t border-white/5">
                <div class="text-center">
                    <p id="stat-posts" class="font-black text-white text-lg">0</p>
                    <p class="text-xs text-slate-500">Bài viết</p>
                </div>
                <div class="text-center cursor-pointer hover:bg-white/5 rounded-xl p-1 transition-colors" onclick="showFollowerModal('followers')">
                    <p id="stat-followers" class="font-black text-white text-lg">0</p>
                    <p class="text-xs text-slate-500">Người theo dõi</p>
                </div>
                <div class="text-center cursor-pointer hover:bg-white/5 rounded-xl p-1 transition-colors" onclick="showFollowerModal('following')">
                    <p id="stat-following" class="font-black text-white text-lg">0</p>
                    <p class="text-xs text-slate-500">Đang theo dõi</p>
                </div>
                <div class="text-center">
                    <p id="stat-points" class="font-black text-lg gradient-text">0</p>
                    <p class="text-xs text-slate-500">Điểm</p>
                </div>
            </div>

            <!-- Level progress bar -->
            <div id="level-progress-section" class="mt-4 pt-4 border-t border-white/5"></div>

            <!-- Joined date -->
            <p id="profile-joined" class="text-xs text-slate-600 mt-3"></p>
        </div>

        <!-- Posts feed -->
        <div id="profile-posts" class="space-y-4"></div>

        <!-- Load more -->
        <div id="profile-sentinel" class="py-4 text-center">
            <div id="profile-loader" class="hidden">
                <svg class="animate-spin w-5 h-5 mx-auto text-brand-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v4m0 12v4M4.93 4.93l2.83 2.83m8.48 8.48l2.83 2.83M2 12h4m12 0h4M4.93 19.07l2.83-2.83m8.48-8.48l2.83-2.83"/></svg>
            </div>
            <p id="profile-end" class="hidden text-xs text-slate-600">Đã xem hết bài viết</p>
        </div>
    </div>
</div>

<!-- Followers/Following Modal -->
<div id="follow-modal" class="modal-overlay hidden">
    <div class="modal-box" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between mb-4">
            <h3 id="follow-modal-title" class="text-lg font-bold text-white">Người theo dõi</h3>
            <button onclick="document.getElementById('follow-modal').classList.add('hidden')" class="btn-ghost p-2">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <div id="follow-modal-list" class="space-y-3 max-h-96 overflow-y-auto"></div>
    </div>
</div>

<script>
const PROFILE_USERNAME = '<?php echo addslashes($username); ?>';
let profilePage  = 1;
let profileMore  = true;
let profileLoading = false;
let profileData  = null;

async function loadProfile(page = 1) {
    if (profileLoading || !profileMore) return;
    profileLoading = true;
    if (page > 1) document.getElementById('profile-loader').classList.remove('hidden');

    try {
        const data = await API.get(`/api/users/profile.php?username=${encodeURIComponent(PROFILE_USERNAME)}&page=${page}`);
        profileData = data;

        if (page === 1) {
            renderProfileInfo(data.user, data.total);
            document.getElementById('profile-skeleton').classList.add('hidden');
            document.getElementById('profile-content').classList.remove('hidden');
        }

        const feed = document.getElementById('profile-posts');
        if (data.posts.length === 0 && page === 1) {
            feed.innerHTML = `<div class="text-center py-12"><p class="text-4xl mb-3">📝</p><p class="text-slate-400">Chưa có bài viết nào</p></div>`;
        } else {
            data.posts.forEach(p => feed.insertAdjacentHTML('beforeend', renderPost(p)));
        }

        profileMore = data.has_more;
        profilePage = page + 1;
        if (!profileMore) document.getElementById('profile-end').classList.remove('hidden');

    } catch(e) {
        document.getElementById('profile-skeleton').innerHTML = `<div class="text-center py-16"><p class="text-5xl mb-4">😢</p><p class="text-slate-400">Người dùng không tồn tại</p><a href="/" class="btn-secondary mt-4 inline-block">Về trang chủ</a></div>`;
    } finally {
        profileLoading = false;
        document.getElementById('profile-loader').classList.add('hidden');
    }
}

function renderProfileInfo(u, totalPosts) {
    document.getElementById('profile-banner').src = u.banner;
    document.getElementById('profile-avatar').src  = u.avatar;
    document.getElementById('profile-display-name').textContent = u.display_name || u.username;
    document.getElementById('profile-username-text').textContent = '@' + u.username;
    document.getElementById('profile-joined').textContent = '📅 Tham gia từ ' + u.joined;

    const lvl = u.level_info;
    document.getElementById('profile-level-badge').innerHTML = `
        <span class="level-badge" style="background:${lvl.color}18;border-color:${lvl.color}35;color:${lvl.color};">
            ${lvl.icon} Lv.${u.level} · ${lvl.name}
        </span>`;

    // Bio
    const bioEl  = document.getElementById('profile-bio');
    const bioTA  = document.getElementById('bio-textarea');
    bioEl.textContent  = u.bio || (u.is_own ? 'Nhấn "Sửa hồ sơ" để thêm bio' : 'Người dùng chưa có bio.');
    bioEl.className    = u.bio ? 'text-sm text-slate-300 leading-relaxed' : 'text-sm text-slate-500 italic';
    bioTA.value        = u.bio || '';

    // Stats
    document.getElementById('stat-posts').textContent     = formatNum(totalPosts);
    document.getElementById('stat-followers').textContent = formatNum(u.follower_count);
    document.getElementById('stat-following').textContent = formatNum(u.following_count);
    document.getElementById('stat-points').textContent    = formatNum(u.points);

    // Level progress
    if (u.level < 10) {
        const pr = u.progress;
        document.getElementById('level-progress-section').innerHTML = `
            <div class="flex justify-between text-xs text-slate-500 mb-1.5">
                <span>${lvl.icon} Lv.${u.level} ${lvl.name}</span>
                <span>${pr.needed > 0 ? 'Còn ' + formatNum(pr.needed) + ' điểm → Lv.' + pr.next_lvl : 'Tối đa!'}</span>
            </div>
            <div class="progress-bar">
                <div class="progress-fill" style="width:${pr.percent}%;background:linear-gradient(90deg,${lvl.color},${lvl.color}88);"></div>
            </div>`;
    } else {
        document.getElementById('level-progress-section').innerHTML = `<p class="text-center text-sm gradient-text-gold font-bold">🔱 MAX LEVEL — Huyền Thoại!</p>`;
    }

    // Action buttons
    const actionsEl = document.getElementById('profile-actions');
    if (u.is_own) {
        actionsEl.innerHTML = `
            <button onclick="toggleBioEdit()" class="btn-secondary text-sm">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                Sửa hồ sơ
            </button>
            <a href="/?page=settings" class="btn-ghost text-sm">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                Cài đặt
            </a>`;
    } else if (window.AnkForum?.user) {
        actionsEl.innerHTML = `
            <button id="follow-toggle-btn" onclick="handleFollow('${u.id}', this)"
                class="follow-btn ${u.is_following ? 'following' : 'not-following'}">
                ${u.is_following ? '✓ Đang theo dõi' : '+ Theo dõi'}
            </button>`;
    }
}

function renderPost(p) {
    const lvl = p.author.level_info;
    const images = ['image/jpeg','image/png','image/gif','image/webp'];
    const videos = ['video/mp4','video/webm','video/ogg','video/quicktime'];
    let mediaHtml = '';
    if (p.media?.length) {
        const count = Math.min(p.media.length, 4);
        mediaHtml = `<div class="post-media mb-4"><div class="post-media-grid count-${count}">`;
        p.media.slice(0,4).forEach((m, i) => {
            const isImg = images.includes(m.mime);
            const isVid = videos.includes(m.mime);
            const isLast = i===3 && p.media.length>4;
            mediaHtml += `<div class="relative overflow-hidden" style="border-radius:10px;max-height:350px;">`;
            if (isImg) mediaHtml += `<img src="${m.path}" style="width:100%;height:100%;object-fit:cover;cursor:zoom-in;" onclick="Lightbox.open(this.src)">`;
            else if (isVid) mediaHtml += `<video controls style="width:100%;height:100%;object-fit:cover;" preload="metadata"><source src="${m.path}"></video>`;
            if (isLast) mediaHtml += `<div class="absolute inset-0 bg-black/70 flex items-center justify-center cursor-pointer" onclick="location.href='/?page=post&id=${p.id}'" style="border-radius:10px;"><span class="text-2xl font-black text-white">+${p.media.length-4}</span></div>`;
            mediaHtml += `</div>`;
        });
        mediaHtml += `</div></div>`;
    }

    const esc = s => s?.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;') || '';
    return `
    <article class="post-card animate-slide-up" data-post-id="${p.id}">
        <div class="p-5">
            <div class="flex items-start justify-between mb-3">
                <div class="flex items-center gap-3">
                    <img src="${p.author.avatar}" class="avatar w-10 h-10">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-sm text-white">${esc(p.author.username)}</span>
                            <span class="level-badge" style="background:${lvl.color}18;border-color:${lvl.color}35;color:${lvl.color};font-size:10px;">${lvl.icon} Lv.${p.author.level}</span>
                        </div>
                        <p class="text-xs text-slate-500">${p.time_ago}</p>
                    </div>
                </div>
                <div class="relative">
                    <button data-dropdown="pmenu-${p.id}" class="btn-ghost p-2 rounded-lg">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="5" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="12" cy="19" r="1.5"/></svg>
                    </button>
                    <div id="pmenu-${p.id}" class="dropdown-menu absolute right-0 top-full hidden" style="min-width:150px;">
                        <a href="/?page=post&id=${p.id}" class="dropdown-item">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            Xem bài viết
                        </a>
                        ${window.AnkForum?.user?.id === p.author.id ? `
                        <button onclick="deletePost('${p.id}')" class="dropdown-item danger w-full text-left">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                            Xóa bài viết
                        </button>` : ''}
                    </div>
                </div>
            </div>
            ${p.content ? `<div class="text-sm text-slate-200 leading-relaxed mb-3 whitespace-pre-line">${esc(p.content)}</div>` : ''}
            ${mediaHtml}
            <div class="flex items-center gap-2 pt-3 border-t border-white/5">
                <button onclick="toggleLike('${p.id}', this)" class="like-btn ${p.liked ? 'liked' : ''}">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="${p.liked ? 'currentColor' : 'none'}" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                    <span class="like-count">${formatNum(p.like_count)}</span>
                </button>
                <a href="/?page=post&id=${p.id}#comments" class="like-btn hover:bg-brand-500/10 hover:text-brand-400">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                    <span>${formatNum(p.comment_count)}</span>
                </a>
            </div>
        </div>
    </article>`;
}

// ── Actions ───────────────────────────────────────────────────

async function handleFollow(userId, btn) {
    try {
        const res = await API.post('/api/users/follow.php', { user_id: userId });
        btn.className = 'follow-btn ' + (res.following ? 'following' : 'not-following');
        btn.textContent = res.following ? '✓ Đang theo dõi' : '+ Theo dõi';
        const stat = document.getElementById('stat-followers');
        stat.textContent = formatNum(res.follower_count);
        Toast.success(res.message);
    } catch(e) { Toast.error('Vui lòng đăng nhập'); }
}

async function toggleLike(postId, btn) {
    if (!window.AnkForum?.user) { Toast.info('Đăng nhập để thích'); return; }
    try {
        const res = await API.post('/api/posts/like.php', { post_id: postId });
        btn.classList.toggle('liked', res.liked);
        btn.querySelector('svg').setAttribute('fill', res.liked ? 'currentColor' : 'none');
        btn.querySelector('.like-count').textContent = formatNum(res.count);
    } catch(e) {}
}

async function deletePost(postId) {
    if (!confirm('Xóa bài viết này?')) return;
    try {
        await API.delete('/api/posts/delete.php', { post_id: postId });
        const el = document.querySelector(`[data-post-id="${postId}"]`);
        if (el) { el.style.opacity='0'; el.style.transition='all 0.3s'; setTimeout(()=>el.remove(),300); }
        Toast.success('Đã xóa bài viết');
    } catch(e) { Toast.error(e.error || 'Không thể xóa'); }
}

function copyLink(id) { navigator.clipboard.writeText(location.origin+'/?page=post&id='+id); Toast.success('Đã sao chép!'); }

// ── Bio edit ──────────────────────────────────────────────────
function toggleBioEdit() {
    document.getElementById('profile-bio').classList.toggle('hidden');
    document.getElementById('bio-edit-form').classList.toggle('hidden');
    document.getElementById('bio-textarea').focus();
}
function cancelBio() {
    document.getElementById('profile-bio').classList.remove('hidden');
    document.getElementById('bio-edit-form').classList.add('hidden');
}
async function saveBio() {
    const bio = document.getElementById('bio-textarea').value.trim();
    const fd  = new FormData();
    fd.append('bio', bio);
    try {
        const res = await API.post('/api/users/update.php', fd);
        document.getElementById('profile-bio').textContent = bio || 'Nhấn "Sửa hồ sơ" để thêm bio.';
        cancelBio();
        Toast.success('Đã cập nhật bio!');
    } catch(e) { Toast.error(e.error || 'Lỗi'); }
}

// ── Avatar/Banner upload ──────────────────────────────────────
async function uploadAvatar(input) {
    const file = input.files[0];
    if (!file) return;
    if (!file.type.startsWith('image/')) { Toast.error('Chỉ chấp nhận file ảnh'); return; }

    Toast.info('Đang upload ảnh đại diện...');
    const fd = new FormData();
    fd.append('avatar', file);

    try {
        const res = await fetch('/api/users/update.php', {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: fd
        });
        const data = await res.json();
        if (!res.ok || !data.success) {
            Toast.error(data.error || 'Lỗi upload avatar');
            return;
        }
        document.getElementById('profile-avatar').src = data.user.avatar + '?t=' + Date.now();
        Toast.success('Đã cập nhật ảnh đại diện!');
    } catch(e) {
        Toast.error('Lỗi kết nối khi upload avatar');
    }
    input.value = '';
}

async function uploadBanner(input) {
    const file = input.files[0];
    if (!file) return;
    if (!file.type.startsWith('image/')) { Toast.error('Chỉ chấp nhận file ảnh'); return; }

    Toast.info('Đang upload ảnh bìa...');
    const fd = new FormData();
    fd.append('banner', file);

    try {
        const res = await fetch('/api/users/update.php', {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: fd
        });
        const data = await res.json();
        if (!res.ok || !data.success) {
            Toast.error(data.error || 'Lỗi upload banner');
            return;
        }
        document.getElementById('profile-banner').src = data.user.banner + '?t=' + Date.now();
        Toast.success('Đã cập nhật ảnh bìa!');
    } catch(e) {
        Toast.error('Lỗi kết nối khi upload banner');
    }
    input.value = '';
}

// ── Followers modal ───────────────────────────────────────────
async function showFollowerModal(type) {
    const modal = document.getElementById('follow-modal');
    const title = document.getElementById('follow-modal-title');
    const list  = document.getElementById('follow-modal-list');
    title.textContent = type === 'followers' ? 'Người theo dõi' : 'Đang theo dõi';
    list.innerHTML = '<p class="text-center text-slate-500 text-sm py-4">Đang tải...</p>';
    modal.classList.remove('hidden');
    try {
        const data = await API.get(`/api/users/followers.php?username=${encodeURIComponent(PROFILE_USERNAME)}&type=${type}`);
        if (!data.users.length) {
            list.innerHTML = '<p class="text-center text-slate-500 text-sm py-4">Chưa có ai</p>';
            return;
        }
        list.innerHTML = data.users.map(u => `
            <a href="/?page=profile&u=${encodeURIComponent(u.username)}" class="flex items-center gap-3 p-2 rounded-xl hover:bg-white/5 transition-colors">
                <img src="${u.avatar}" class="avatar w-10 h-10">
                <div>
                    <p class="font-semibold text-sm text-white">${u.username}</p>
                    <p class="text-xs" style="color:${u.level_info.color}">${u.level_info.icon} Lv.${u.level}</p>
                </div>
            </a>`).join('');
    } catch(e) {
        list.innerHTML = '<p class="text-center text-red-400 text-sm py-4">Lỗi tải dữ liệu</p>';
    }
}

document.getElementById('follow-modal')?.addEventListener('click', function(e) {
    if (e.target === this) this.classList.add('hidden');
});

// ── Infinite scroll ───────────────────────────────────────────
const profileObserver = new IntersectionObserver((entries) => {
    if (entries[0].isIntersecting && profileMore && !profileLoading) {
        loadProfile(profilePage);
    }
}, { rootMargin: '300px' });

document.addEventListener('DOMContentLoaded', () => {
    loadProfile(1);
    const sentinel = document.getElementById('profile-sentinel');
    if (sentinel) profileObserver.observe(sentinel);
});
</script>
