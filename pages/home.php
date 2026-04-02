<?php
// pages/home.php
$user = currentUser();
?>

<!-- Post Composer -->
<?php if ($user): ?>
<div class="glass-card p-5 mb-4 animate-fade-in">
    <div class="flex gap-3">
        <a href="/?page=profile&u=<?php echo urlencode($user['username']); ?>" class="flex-shrink-0">
            <img src="<?php echo avatarUrl($user['avatar']); ?>"
                alt="<?php echo sanitize($user['username']); ?>"
                class="avatar w-11 h-11">
        </a>
        <div class="flex-1 min-w-0">
            <div onclick="document.getElementById('compose-modal').style.display='flex'"
                class="input-field cursor-text flex items-center text-slate-500 text-sm"
                style="min-height:48px;">
                Bạn đang nghĩ gì, <?php echo sanitize($user['username']); ?>?
            </div>
        </div>
    </div>
    <div class="flex items-center gap-2 mt-3 pt-3 border-t border-white/5">
        <button onclick="document.getElementById('compose-modal').style.display='flex'"
            class="btn-ghost flex-1 justify-center text-sm gap-2">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#22d3ee" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
            <span class="text-cyan-400">Ảnh/Video</span>
        </button>
        <button onclick="document.getElementById('compose-modal').style.display='flex'"
            class="btn-ghost flex-1 justify-center text-sm gap-2">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#34d399" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
            <span class="text-emerald-400">Bài viết</span>
        </button>
        <button onclick="window.location.href='/?page=broadcast'"
            class="btn-ghost flex-1 justify-center text-sm gap-2">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2"><circle cx="12" cy="12" r="1"/><path d="M21 12a9 9 0 0 1-9 9m0 0a9 9 0 0 1-9-9m9 9v-2m0-14a9 9 0 0 1 9 9"/></svg>
            <span class="text-red-400">Livestream</span>
        </button>
        <button onclick="window.location.href='/?page=create-poll'"
            class="btn-ghost flex-1 justify-center text-sm gap-2">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2"><path d="M3 12h18M12 3v18M6 9h12M6 15h12"/></svg>
            <span class="text-amber-400">Poll</span>
        </button>
    </div>
</div>

<!-- Compose Modal -->
<div id="compose-modal" class="modal-overlay" style="display:none;">
    <div class="modal-box w-full max-w-lg" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-3">
                <img src="<?php echo avatarUrl($user['avatar']); ?>" class="avatar w-10 h-10">
                <div>
                    <p class="font-bold text-sm text-white"><?php echo sanitize($user['username']); ?></p>
                    <p class="text-xs text-slate-500">Đăng công khai</p>
                </div>
            </div>
            <button onclick="document.getElementById('compose-modal').style.display='none'" class="btn-ghost p-2 rounded-lg">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>

        <form id="compose-form">
            <textarea id="compose-content" class="composer-textarea auto-resize input-field"
                placeholder="Bạn đang nghĩ gì? Chia sẻ với cộng đồng..."
                data-max-length="5000" data-counter-id="char-count"
                rows="4" autofocus></textarea>

            <div class="flex justify-end mt-1 mb-3">
                <span id="char-count" class="text-xs text-muted">5000</span>
            </div>

            <!-- Media preview -->
            <div id="media-preview" class="flex flex-wrap gap-2 mb-3"></div>

            <!-- File input (hidden) -->
            <input type="file" id="media-input" multiple accept="image/*,video/*,application/pdf"
                class="hidden" onchange="previewFiles(this)">

            <div class="flex items-center justify-between pt-3 border-t border-white/5">
                <div class="flex gap-2">
                    <button type="button" onclick="document.getElementById('media-input').click()"
                        class="btn-ghost p-2.5 rounded-xl" title="Thêm ảnh/video">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#22d3ee" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                    </button>
                </div>
                <div class="flex gap-2">
                    <button type="button" onclick="document.getElementById('compose-modal').style.display='none'"
                        class="btn-secondary text-sm px-4">Hủy</button>
                    <button type="submit" id="post-btn" class="btn-primary text-sm px-5">
                        <span id="post-btn-text">Đăng bài</span>
                        <span id="post-btn-loader" class="hidden">
                            <svg class="animate-spin w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v4m0 12v4M4.93 4.93l2.83 2.83m8.48 8.48l2.83 2.83M2 12h4m12 0h4M4.93 19.07l2.83-2.83m8.48-8.48l2.83-2.83"/></svg>
                        </span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php else: ?>
<!-- Guest CTA -->
<div class="glass-card p-6 mb-4 text-center animate-fade-in">
    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-brand-500 to-brand-700 flex items-center justify-center mx-auto mb-4 glow-brand">
        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
    </div>
    <h2 class="text-lg font-bold text-white mb-1">Chào mừng đến AnkForum</h2>
    <p class="text-slate-400 text-sm mb-5">Đăng nhập để tham gia cộng đồng và chia sẻ nội dung</p>
    <div class="flex gap-3 justify-center">
        <a href="/?page=login" class="btn-secondary">Đăng nhập</a>
        <a href="/?page=register" class="btn-primary">Đăng ký miễn phí</a>
    </div>
</div>
<?php endif; ?>

<!-- Livestream Section -->
<div id="livestream-section" class="mb-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-bold text-white flex items-center gap-2">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-red-500 animate-pulse">
                <circle cx="12" cy="12" r="10"/>
                <circle cx="12" cy="12" r="6" fill="currentColor"/>
            </svg>
            Đang phát trực tiếp
        </h2>
        <a href="/?page=broadcasts" class="text-sm text-brand-400 hover:underline">Xem tất cả</a>
    </div>
    <div id="livestreams-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4"></div>
</div>

<!-- Standalone Polls Section -->
<div id="polls-section" class="mb-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-bold text-white flex items-center gap-2">
            📊 Poll hot
        </h2>
        <a href="/?page=create-poll" class="text-sm text-brand-400 hover:underline">Tạo poll</a>
    </div>
    <div id="polls-container" class="grid grid-cols-1 md:grid-cols-2 gap-4"></div>
</div>

<!-- Feed -->
<div id="posts-feed" class="space-y-4">
    <!-- Skeleton loaders -->
    <?php for ($s = 0; $s < 3; $s++): ?>
    <div class="post-card p-5 skeleton-post">
        <div class="flex gap-3 mb-4">
            <div class="skeleton w-11 h-11 rounded-full flex-shrink-0"></div>
            <div class="flex-1 space-y-2">
                <div class="skeleton h-3 w-32 rounded"></div>
                <div class="skeleton h-2 w-20 rounded"></div>
            </div>
        </div>
        <div class="space-y-2 mb-4">
            <div class="skeleton h-3 w-full rounded"></div>
            <div class="skeleton h-3 w-5/6 rounded"></div>
            <div class="skeleton h-3 w-4/6 rounded"></div>
        </div>
        <div class="flex gap-3 pt-3 border-t border-white/5">
            <div class="skeleton h-8 w-20 rounded-lg"></div>
            <div class="skeleton h-8 w-20 rounded-lg"></div>
        </div>
    </div>
    <?php endfor; ?>
</div>

<!-- Infinite scroll sentinel -->
<div id="scroll-sentinel" class="py-4 text-center">
    <div id="load-more-indicator" class="hidden">
        <svg class="animate-spin w-5 h-5 mx-auto text-brand-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v4m0 12v4M4.93 4.93l2.83 2.83m8.48 8.48l2.83 2.83M2 12h4m12 0h4M4.93 19.07l2.83-2.83m8.48-8.48l2.83-2.83"/></svg>
    </div>
    <p id="no-more-posts" class="hidden text-xs text-slate-600">Bạn đã xem hết bài viết 🎉</p>
</div>

<script>
// ── Feed ──────────────────────────────────────────────────────
let feedPage = 1;
let feedLoading = false;
let feedHasMore = true;

async function loadFeed(reset = false) {
    if (feedLoading || !feedHasMore) return;
    feedLoading = true;

    if (reset) {
        feedPage = 1;
        feedHasMore = true;
        document.getElementById('posts-feed').innerHTML = '';
    }

    document.getElementById('load-more-indicator').classList.remove('hidden');

    try {
        const data = await API.get('/api/posts/feed.php?page=' + feedPage);

        // Remove skeletons on first load
        document.querySelectorAll('.skeleton-post').forEach(el => el.remove());

        const feed = document.getElementById('posts-feed');
        if (data.posts.length === 0 && feedPage === 1) {
            feed.innerHTML = `
                <div class="text-center py-16 animate-fade-in">
                    <div class="text-5xl mb-4">📭</div>
                    <p class="text-slate-400 font-medium">Chưa có bài viết nào</p>
                    <p class="text-slate-600 text-sm mt-1">Hãy là người đầu tiên chia sẻ!</p>
                </div>`;
        } else {
            data.posts.forEach(post => {
                feed.insertAdjacentHTML('beforeend', renderPost(post));
            });
        }

        feedHasMore = data.has_more;
        feedPage++;

        if (!feedHasMore) {
            document.getElementById('no-more-posts').classList.remove('hidden');
        }
    } catch (err) {
        Toast.error('Không thể tải bài viết');
    } finally {
        feedLoading = false;
        document.getElementById('load-more-indicator').classList.add('hidden');
    }
}

function renderPost(p) {
    const lvlColors = {'#94a3b8':'','#22d3ee':'','#34d399':'','#818cf8':'','#f59e0b':''};
    const lvl = p.author.level_info;
    const mediaHtml = renderMedia(p.media);

    return `
    <article class="post-card animate-slide-up" data-post-id="${p.id}">
        <div class="p-5">
            <div class="flex items-start justify-between mb-4">
                <div class="flex items-center gap-3">
                    <a href="/?page=profile&u=${encodeURIComponent(p.author.username)}" class="relative flex-shrink-0">
                        <img src="${p.author.avatar}" alt="${p.author.username}" class="avatar w-11 h-11">
                    </a>
                    <div>
                        <div class="flex items-center gap-2 flex-wrap">
                            <a href="/?page=profile&u=${encodeURIComponent(p.author.username)}"
                                class="font-bold text-sm text-white hover:text-brand-300 transition-colors">
                                ${escHtml(p.author.username)}
                            </a>
                            <span class="level-badge" style="background:${lvl.color}18;border-color:${lvl.color}35;color:${lvl.color};font-size:10px;">
                                ${lvl.icon} Lv.${p.author.level}
                            </span>
                        </div>
                        <p class="text-xs text-slate-500 mt-0.5">${p.time_ago}</p>
                    </div>
                </div>
                <div class="relative">
                    <button data-dropdown="post-menu-${p.id}" class="btn-ghost p-2 rounded-lg">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="5" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="12" cy="19" r="1.5"/></svg>
                    </button>
                    <div id="post-menu-${p.id}" class="dropdown-menu absolute right-0 top-full hidden" style="min-width:160px;">
                        <a href="/?page=post&id=${p.id}" class="dropdown-item">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            Xem bài viết
                        </a>
                        <button onclick="copyLink('${p.id}')" class="dropdown-item w-full text-left">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                            Sao chép link
                        </button>
                        ${window.AnkForum?.user?.id === p.author.id ? `
                        <div class="border-t border-white/5 mt-1 pt-1">
                            <button onclick="deletePost('${p.id}')" class="dropdown-item danger w-full text-left">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                                Xóa bài viết
                            </button>
                        </div>` : ''}
                    </div>
                </div>
            </div>
            ${p.content ? `<div class="text-sm text-slate-200 leading-relaxed mb-4 whitespace-pre-line">${escHtml(p.content)}</div>` : ''}
            ${mediaHtml ? `<div class="post-media mb-4">${mediaHtml}</div>` : ''}
            <div class="flex items-center gap-2 pt-3 border-t border-white/5">
                <button onclick="toggleLike('${p.id}', this)" class="like-btn ${p.liked ? 'liked' : ''}">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="${p.liked ? 'currentColor' : 'none'}" stroke="currentColor" stroke-width="2">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                    </svg>
                    <span class="like-count">${formatNum(p.like_count)}</span>
                </button>
                <a href="/?page=post&id=${p.id}#comments" class="like-btn hover:bg-brand-500/10 hover:text-brand-400 hover:border-brand-500/20">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                    <span>${formatNum(p.comment_count)}</span>
                </a>
                <button onclick="copyLink('${p.id}')" class="like-btn hover:bg-cyan-500/10 hover:text-cyan-400 hover:border-cyan-500/20">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>
                </button>
            </div>
        </div>
    </article>`;
}

function renderMedia(media) {
    if (!media || !media.length) return '';
    const count = Math.min(media.length, 4);
    const images = ['image/jpeg','image/png','image/gif','image/webp'];
    const videos = ['video/mp4','video/webm','video/ogg','video/quicktime'];

    let html = `<div class="post-media-grid count-${count}">`;
    media.slice(0, 4).forEach((m, i) => {
        const isImg = images.includes(m.mime);
        const isVid = videos.includes(m.mime);
        const isLast = i === 3 && media.length > 4;
        html += `<div class="relative overflow-hidden" style="border-radius:10px;max-height:350px;">`;
        if (isImg) {
            html += `<img src="${m.path}" style="width:100%;height:100%;object-fit:cover;cursor:zoom-in;" onclick="Lightbox.open(this.src)">`;
        } else if (isVid) {
            html += `<video controls style="width:100%;height:100%;object-fit:cover;" preload="metadata"><source src="${m.path}"></video>`;
        }
        if (isLast) {
            html += `<div onclick="location.href='/?page=post&id=${media[0]?.postId}'" class="absolute inset-0 bg-black/70 flex items-center justify-center cursor-pointer" style="border-radius:10px;"><span class="text-2xl font-black text-white">+${media.length-4}</span></div>`;
        }
        html += `</div>`;
    });
    html += `</div>`;
    return html;
}

function escHtml(str) {
    return str.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

// ── Like ──────────────────────────────────────────────────────
async function toggleLike(postId, btn) {
    if (!window.AnkForum?.user) { Toast.info('Đăng nhập để thích bài viết'); return; }
    try {
        const res = await API.post('/api/posts/like.php', { post_id: postId });
        btn.classList.toggle('liked', res.liked);
        const svg = btn.querySelector('svg');
        svg.setAttribute('fill', res.liked ? 'currentColor' : 'none');
        btn.querySelector('.like-count').textContent = formatNum(res.count);
    } catch(e) {}
}

// ── Delete ────────────────────────────────────────────────────
async function deletePost(postId) {
    if (!confirm('Bạn có chắc muốn xóa bài viết này?')) return;
    try {
        await API.delete('/api/posts/delete.php', { post_id: postId });
        const el = document.querySelector(`[data-post-id="${postId}"]`);
        if (el) { el.style.opacity = '0'; el.style.transform = 'scale(0.95)'; el.style.transition = 'all 0.3s'; setTimeout(() => el.remove(), 300); }
        Toast.success('Đã xóa bài viết');
    } catch(e) { Toast.error(e.error || 'Không thể xóa'); }
}

// ── Copy link ─────────────────────────────────────────────────
function copyLink(postId) {
    navigator.clipboard.writeText(location.origin + '/?page=post&id=' + postId);
    Toast.success('Đã sao chép link bài viết!');
}

// ── Compose ───────────────────────────────────────────────────
function previewFiles(input) {
    const preview = document.getElementById('media-preview');
    preview.innerHTML = '';
    Array.from(input.files).forEach((file, i) => {
        const div = document.createElement('div');
        div.className = 'upload-preview';
        if (file.type.startsWith('image/')) {
            const img = document.createElement('img');
            img.style.cssText = 'width:80px;height:80px;object-fit:cover;border-radius:8px;';
            const reader = new FileReader();
            reader.onload = e => img.src = e.target.result;
            reader.readAsDataURL(file);
            div.appendChild(img);
        } else {
            div.innerHTML = `<div style="width:80px;height:80px;background:rgba(99,102,241,0.1);border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:11px;color:#818cf8;text-align:center;padding:4px;">${file.name.slice(0,15)}</div>`;
        }
        const rm = document.createElement('button');
        rm.className = 'remove-btn';
        rm.innerHTML = '×';
        rm.onclick = () => { div.remove(); };
        div.appendChild(rm);
        preview.appendChild(div);
    });
}

document.getElementById('compose-form')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    const content = document.getElementById('compose-content').value;
    const files   = document.getElementById('media-input').files;

    if (!content.trim() && !files.length) { Toast.error('Vui lòng nhập nội dung'); return; }

    const btn      = document.getElementById('post-btn');
    const btnText  = document.getElementById('post-btn-text');
    const btnLoader= document.getElementById('post-btn-loader');
    btn.disabled   = true;
    btnText.classList.add('hidden');
    btnLoader.classList.remove('hidden');

    const fd = new FormData();
    fd.append('content', content);
    Array.from(files).forEach(f => fd.append('media[]', f));

    try {
        const res = await API.post('/api/posts/create.php', fd);
        document.getElementById('compose-modal').style.display = 'none';
        document.getElementById('compose-content').value = '';
        document.getElementById('media-preview').innerHTML = '';
        document.getElementById('media-input').value = '';

        // Prepend new post to feed
        const feed = document.getElementById('posts-feed');
        document.querySelectorAll('.skeleton-post').forEach(el => el.remove());

        const wrapper = document.createElement('div');
        wrapper.innerHTML = renderPost({
            ...res.post,
            like_count: 0,
            liked: false,
            comment_count: 0,
            time_ago: 'Vừa xong',
            author: {
                id: window.AnkForum.user.id,
                username: window.AnkForum.user.username,
                avatar: window.AnkForum.user.avatar,
                level: window.AnkForum.user.level,
                level_info: {icon:'🌱',color:'#94a3b8',name:'Newbie'},
            }
        });
        feed.prepend(wrapper.firstElementChild);
        Toast.success(res.message || 'Đăng bài thành công!');
    } catch(err) {
        Toast.error(err.error || 'Không thể đăng bài');
    } finally {
        btn.disabled = false;
        btnText.classList.remove('hidden');
        btnLoader.classList.add('hidden');
    }
});

// Close modal on overlay click
document.getElementById('compose-modal')?.addEventListener('click', function(e) {
    if (e.target === this) this.style.display = 'none';
});

// ── Livestreams ────────────────────────────────────────────
async function loadLivestreams() {
    try {
        const res = await API.get('/api/livestreams/list.php?status=live');
        const container = document.getElementById('livestreams-container');
        
        if (res.streams.length === 0) {
            document.getElementById('livestream-section').style.display = 'none';
            return;
        }
        
        container.innerHTML = res.streams.map(stream => `
            <div class="bg-slate-800 rounded-lg overflow-hidden hover:bg-slate-700 transition cursor-pointer"
                 onclick="window.location.href='/?page=livestream&id=${stream.id}'">
                <div class="relative bg-black aspect-video flex items-center justify-center">
                    <svg class="w-12 h-12 text-slate-600" fill="currentColor" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6" fill="currentColor"/>
                    </svg>
                    <div class="absolute top-2 right-2 bg-red-600 px-2 py-1 rounded text-xs font-bold text-white animate-pulse">
                        LIVE
                    </div>
                </div>
                <div class="p-3">
                    <h4 class="font-semibold text-white text-sm truncate">${escHtml(stream.title)}</h4>
                    <div class="flex items-center gap-2 text-xs text-slate-400 mt-1">
                        <span>👤 ${escHtml(stream.author_name)}</span>
                        <span>•</span>
                        <span>👥 ${stream.viewer_count} viewers</span>
                    </div>
                </div>
            </div>
        `).join('');
    } catch(e) {
        console.error('Error loading livestreams:', e);
    }
}

// ── Polls ────────────────────────────────────────────────
async function loadPolls() {
    try {
        const res = await API.get('/api/polls/list.php?standalone=1');
        const container = document.getElementById('polls-container');
        
        if (res.polls.length === 0) {
            document.getElementById('polls-section').style.display = 'none';
            return;
        }
        
        container.innerHTML = res.polls.slice(0, 4).map(poll => {
            const total = poll.options.reduce((sum, opt) => sum + opt.votes, 0);
            return `
                <div class="bg-slate-800 rounded-lg p-4 border border-slate-700 hover:border-brand-500 transition cursor-pointer"
                     onclick="document.getElementById('poll-${poll.id}').scrollIntoView({behavior: 'smooth'})">
                    <h4 class="font-semibold text-white text-sm mb-2 truncate">${escHtml(poll.title)}</h4>
                    <div class="space-y-2 mb-3">
                        ${poll.options.slice(0, 3).map(opt => {
                            const pct = total > 0 ? Math.round((opt.votes / total) * 100) : 0;
                            return `
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-slate-400 truncate">${escHtml(opt.text)}</span>
                                    <span class="text-brand-400 font-semibold">${pct}%</span>
                                </div>
                            `;
                        }).join('')}
                    </div>
                    <div class="text-xs text-slate-500 flex items-center justify-between">
                        <span>${total} bình chọn</span>
                        <span class="text-brand-400">Chi tiết →</span>
                    </div>
                </div>
            `;
        }).join('');
    } catch(e) {
        console.error('Error loading polls:', e);
    }
}

// Load on page init
document.addEventListener('DOMContentLoaded', () => {
    loadLivestreams();
    loadPolls();
});

// Auto-refresh livestreams every 10 seconds
setInterval(loadLivestreams, 10000);

</script>
