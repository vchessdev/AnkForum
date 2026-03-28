<?php
// pages/post.php
$user   = currentUser();
$postId = $_GET['id'] ?? '';

if (empty($postId)) { echo '<p class="text-center text-slate-400 py-16">Bài viết không tồn tại</p>'; return; }

$posts    = readJson(POSTS_FILE);
$post     = null;
foreach ($posts as $p) { if ($p['id'] === $postId) { $post = $p; break; } }

if (!$post) { echo '<div class="text-center py-16"><p class="text-5xl mb-4">🔍</p><p class="text-slate-400">Bài viết không tồn tại hoặc đã bị xóa</p></div>'; return; }

$author  = getUserById($post['user_id']);
$lvlInfo = getLevelInfo($author['level'] ?? 1);
$likes   = $post['likes'] ?? [];
$liked   = $user ? in_array($user['id'], $likes) : false;
?>

<!-- Back button -->
<a href="/" class="inline-flex items-center gap-2 text-sm text-slate-400 hover:text-white transition-colors mb-4">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
    Quay lại
</a>

<!-- Post detail -->
<div class="post-card animate-fade-in mb-4">
    <div class="p-6">
        <!-- Author -->
        <div class="flex items-center gap-3 mb-5">
            <a href="/?page=profile&u=<?php echo urlencode($author['username']); ?>" class="relative">
                <img src="<?php echo avatarUrl($author['avatar']); ?>"
                    alt="<?php echo sanitize($author['username']); ?>"
                    class="avatar w-12 h-12">
                <?php if ($author['is_online'] ?? false): ?>
                <span class="online-dot"></span>
                <?php endif; ?>
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <a href="/?page=profile&u=<?php echo urlencode($author['username']); ?>"
                        class="font-bold text-white hover:text-brand-300 transition-colors">
                        <?php echo sanitize($author['username']); ?>
                    </a>
                    <span class="level-badge" style="background:<?php echo $lvlInfo['color']; ?>18;border-color:<?php echo $lvlInfo['color']; ?>35;color:<?php echo $lvlInfo['color']; ?>">
                        <?php echo $lvlInfo['icon']; ?> Lv.<?php echo $author['level'] ?? 1; ?> <?php echo $lvlInfo['name']; ?>
                    </span>
                </div>
                <p class="text-xs text-slate-500 mt-0.5"><?php echo formatDate($post['created_at']); ?> · <?php echo timeAgo($post['created_at']); ?></p>
            </div>
        </div>

        <!-- Content -->
        <?php if (!empty($post['content'])): ?>
        <div class="text-base text-slate-200 leading-relaxed mb-5 whitespace-pre-line">
            <?php echo nl2br(sanitize($post['content'])); ?>
        </div>
        <?php endif; ?>

        <!-- Media -->
        <?php if (!empty($post['media'])): ?>
        <div class="post-media mb-5">
            <div class="space-y-2">
                <?php foreach ($post['media'] as $media):
                    $isImage = in_array($media['mime'] ?? '', ALLOWED_IMAGE_TYPES);
                    $isVideo = in_array($media['mime'] ?? '', ALLOWED_VIDEO_TYPES);
                ?>
                <div style="border-radius:12px;overflow:hidden;">
                    <?php if ($isImage): ?>
                    <img src="<?php echo $media['path']; ?>" alt="Media"
                        style="width:100%;border-radius:12px;cursor:zoom-in;max-height:600px;object-fit:contain;background:#0a0a14;"
                        onclick="Lightbox.open(this.src)">
                    <?php elseif ($isVideo): ?>
                    <video controls style="width:100%;border-radius:12px;max-height:500px;" preload="metadata">
                        <source src="<?php echo $media['path']; ?>">
                    </video>
                    <?php else: ?>
                    <a href="<?php echo $media['path']; ?>" target="_blank"
                        class="flex items-center gap-3 p-4 bg-white/5 rounded-xl text-sm text-brand-400 hover:bg-white/10 transition-colors">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                        Tải xuống file
                    </a>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Stats -->
        <div class="flex items-center gap-4 text-xs text-slate-500 mb-4">
            <span><?php echo formatNumber(count($likes)); ?> lượt thích</span>
            <span><?php echo formatNumber($post['comment_count'] ?? 0); ?> bình luận</span>
        </div>

        <!-- Action buttons -->
        <div class="flex items-center gap-2 pt-4 border-t border-white/5">
            <button onclick="toggleLikePost('<?php echo $postId; ?>', this)"
                class="like-btn <?php echo $liked ? 'liked' : ''; ?> flex-1 justify-center">
                <svg width="15" height="15" viewBox="0 0 24 24"
                    fill="<?php echo $liked ? 'currentColor' : 'none'; ?>"
                    stroke="currentColor" stroke-width="2">
                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                </svg>
                <span class="like-count"><?php echo formatNumber(count($likes)); ?></span> Thích
            </button>
            <button onclick="document.getElementById('comment-input')?.focus()"
                class="like-btn flex-1 justify-center hover:bg-brand-500/10 hover:text-brand-400 hover:border-brand-500/20">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                Bình luận
            </button>
            <button onclick="copyLink('<?php echo $postId; ?>')"
                class="like-btn flex-1 justify-center hover:bg-cyan-500/10 hover:text-cyan-400 hover:border-cyan-500/20">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>
                Chia sẻ
            </button>
        </div>
    </div>
</div>

<!-- Comments section -->
<div id="comments" class="glass-card p-6 animate-slide-up">
    <h2 class="text-base font-bold text-white mb-5 flex items-center gap-2">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#818cf8" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
        Bình luận
        <span class="text-slate-500 font-normal text-sm" id="comment-total">(<?php echo $post['comment_count'] ?? 0; ?>)</span>
    </h2>

    <!-- Add comment -->
    <?php if ($user): ?>
    <div class="flex gap-3 mb-6">
        <img src="<?php echo avatarUrl($user['avatar']); ?>" class="avatar w-9 h-9 flex-shrink-0 mt-1">
        <div class="flex-1">
            <div class="flex gap-2">
                <textarea id="comment-input" rows="1"
                    class="input-field auto-resize text-sm"
                    placeholder="Viết bình luận..."></textarea>
                <button id="comment-submit-btn" onclick="submitComment()"
                    class="btn-primary px-4 py-2 flex-shrink-0 self-end">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                </button>
            </div>
        </div>
    </div>
    <?php else: ?>
    <div class="text-center py-4 mb-6">
        <a href="/?page=login" class="btn-secondary text-sm">Đăng nhập để bình luận</a>
    </div>
    <?php endif; ?>

    <!-- Comment list -->
    <div id="comments-list" class="space-y-4"></div>
    <div id="comments-loading" class="text-center py-4">
        <svg class="animate-spin w-5 h-5 mx-auto text-brand-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v4m0 12v4M4.93 4.93l2.83 2.83m8.48 8.48l2.83 2.83M2 12h4m12 0h4M4.93 19.07l2.83-2.83m8.48-8.48l2.83-2.83"/></svg>
    </div>
    <div id="comments-end" class="hidden text-center py-2">
        <button id="load-more-comments" class="btn-ghost text-sm">Xem thêm bình luận</button>
    </div>
</div>

<script>
const POST_ID = '<?php echo $postId; ?>';
let commentPage = 1;
let commentHasMore = true;

async function loadComments(reset = false) {
    if (reset) { commentPage = 1; commentHasMore = true; document.getElementById('comments-list').innerHTML = ''; }
    if (!commentHasMore) return;

    document.getElementById('comments-loading').style.display = 'block';
    try {
        const data = await API.get(`/api/comments/list.php?post_id=${POST_ID}&page=${commentPage}`);
        document.getElementById('comments-loading').style.display = 'none';

        const list = document.getElementById('comments-list');
        if (data.comments.length === 0 && commentPage === 1) {
            list.innerHTML = '<p class="text-center text-slate-500 text-sm py-4">Chưa có bình luận nào. Hãy là người đầu tiên!</p>';
        } else {
            data.comments.forEach(c => list.insertAdjacentHTML('beforeend', renderComment(c)));
        }
        commentHasMore = data.has_more;
        commentPage++;
        document.getElementById('comments-end').classList.toggle('hidden', !commentHasMore);
        if (data.total) document.getElementById('comment-total').textContent = `(${data.total})`;
    } catch(e) {
        document.getElementById('comments-loading').style.display = 'none';
    }
}

function renderComment(c, isReply = false) {
    const lvl = c.author.level_info;
    const repliesHtml = (c.replies||[]).map(r => renderComment(r, true)).join('');
    return `
    <div class="comment-item ${isReply ? 'ml-10 border-l-2 pl-4' : ''}" id="comment-${c.id}" style="${isReply?'border-color:rgba(99,102,241,0.2)':''}">
        <div class="flex gap-2.5">
            <a href="/?page=profile&u=${encodeURIComponent(c.author.username)}" class="flex-shrink-0">
                <img src="${c.author.avatar}" alt="${c.author.username}" class="avatar w-8 h-8">
            </a>
            <div class="flex-1 min-w-0">
                <div class="bg-white/5 rounded-2xl px-4 py-2.5">
                    <div class="flex items-center gap-2 mb-1 flex-wrap">
                        <a href="/?page=profile&u=${encodeURIComponent(c.author.username)}"
                            class="font-bold text-xs text-white hover:text-brand-300 transition-colors">
                            ${c.author.username}
                        </a>
                        <span style="background:${lvl.color}18;border:1px solid ${lvl.color}35;color:${lvl.color};font-size:10px;padding:1px 6px;border-radius:999px;font-weight:700;">
                            ${lvl.icon} Lv.${c.author.level}
                        </span>
                    </div>
                    <p class="text-sm text-slate-200 leading-relaxed">${escHtml(c.content)}</p>
                </div>
                <div class="flex items-center gap-3 mt-1.5 px-2">
                    <span class="text-xs text-slate-600">${c.time_ago}</span>
                    <button onclick="likeComment('${c.id}', this)"
                        class="text-xs font-semibold transition-colors ${c.liked ? 'text-red-400' : 'text-slate-500 hover:text-red-400'}">
                        ♥ <span class="cmt-likes">${c.like_count||0}</span>
                    </button>
                    ${window.AnkForum?.user ? `<button onclick="showReplyBox('${c.id}')" class="text-xs text-slate-500 hover:text-brand-400 font-semibold transition-colors">Trả lời</button>` : ''}
                    ${c.is_owner ? `<button onclick="deleteComment('${c.id}')" class="text-xs text-slate-600 hover:text-red-400 transition-colors">Xóa</button>` : ''}
                </div>
                <!-- Reply box -->
                <div id="reply-box-${c.id}" class="hidden mt-2 flex gap-2">
                    <img src="${window.AnkForum?.user?.avatar || 'assets/images/default-avatar.svg'}" class="avatar w-7 h-7 flex-shrink-0 mt-1">
                    <div class="flex-1 flex gap-2">
                        <input type="text" placeholder="Trả lời ${c.author.username}..."
                            class="input-field text-sm" style="height:36px;padding:6px 12px;"
                            onkeydown="if(event.key==='Enter')submitReply('${c.id}', this.value,this)">
                        <button onclick="submitReply('${c.id}', document.querySelector('#reply-box-${c.id} input').value, document.querySelector('#reply-box-${c.id} input'))"
                            class="btn-primary px-3 py-1.5 flex-shrink-0" style="height:36px;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                        </button>
                    </div>
                </div>
                <!-- Nested replies -->
                ${repliesHtml ? `<div class="mt-3 space-y-3">${repliesHtml}</div>` : ''}
            </div>
        </div>
    </div>`;
}

function escHtml(s) { return s?.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;')||''; }

async function submitComment() {
    const input = document.getElementById('comment-input');
    const content = input.value.trim();
    if (!content) return;
    input.disabled = true;
    try {
        const res = await API.post('/api/comments/add.php', { post_id: POST_ID, content });
        input.value = '';
        input.style.height = 'auto';
        const list = document.getElementById('comments-list');
        if (list.querySelector('p.text-center')) list.innerHTML = '';
        list.insertAdjacentHTML('beforeend', renderComment(res.comment));
        const total = document.getElementById('comment-total');
        const cur = parseInt(total.textContent.replace(/\D/g,'')) || 0;
        total.textContent = `(${cur+1})`;
        Toast.success('+5 điểm! Bình luận đã được gửi');
        list.lastElementChild?.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    } catch(e) { Toast.error(e.error || 'Không thể gửi bình luận'); }
    finally { input.disabled = false; input.focus(); }
}

function showReplyBox(commentId) {
    document.querySelectorAll('[id^="reply-box-"]').forEach(el => el.classList.add('hidden'));
    const box = document.getElementById('reply-box-' + commentId);
    box.classList.toggle('hidden');
    box.classList.remove('hidden');
    box.style.display = 'flex';
    box.querySelector('input')?.focus();
}

async function submitReply(parentId, content, inputEl) {
    content = content?.trim();
    if (!content) return;
    inputEl.disabled = true;
    try {
        const res = await API.post('/api/comments/add.php', { post_id: POST_ID, parent_id: parentId, content });
        document.getElementById('reply-box-' + parentId).style.display = 'none';
        inputEl.value = '';
        // Append reply under parent
        const parent = document.getElementById('comment-' + parentId);
        if (parent) {
            let repliesContainer = parent.querySelector('.replies-container');
            if (!repliesContainer) {
                repliesContainer = document.createElement('div');
                repliesContainer.className = 'replies-container mt-3 space-y-3';
                parent.querySelector('.flex-1')?.appendChild(repliesContainer);
            }
            repliesContainer.insertAdjacentHTML('beforeend', renderComment(res.comment, true));
        }
        Toast.success('Đã trả lời bình luận');
    } catch(e) { Toast.error('Không thể gửi'); }
    finally { inputEl.disabled = false; }
}

async function likeComment(commentId, btn) {
    if (!window.AnkForum?.user) { Toast.info('Đăng nhập để thích bình luận'); return; }
    try {
        const res = await API.post('/api/comments/like.php', { comment_id: commentId });
        btn.classList.toggle('text-red-400', res.liked);
        btn.classList.toggle('text-slate-500', !res.liked);
        btn.querySelector('.cmt-likes').textContent = res.count;
    } catch(e) {}
}

async function deleteComment(commentId) {
    if (!confirm('Xóa bình luận này?')) return;
    try {
        await API.delete('/api/comments/delete.php', { comment_id: commentId });
        const el = document.getElementById('comment-' + commentId);
        if (el) { el.style.opacity='0'; el.style.transition='opacity 0.3s'; setTimeout(()=>el.remove(),300); }
        Toast.success('Đã xóa bình luận');
    } catch(e) { Toast.error('Không thể xóa'); }
}

async function toggleLikePost(postId, btn) {
    if (!window.AnkForum?.user) { Toast.info('Đăng nhập để thích'); return; }
    try {
        const res = await API.post('/api/posts/like.php', { post_id: postId });
        btn.classList.toggle('liked', res.liked);
        btn.querySelector('svg').setAttribute('fill', res.liked ? 'currentColor' : 'none');
        btn.querySelector('.like-count').textContent = res.count;
    } catch(e) {}
}

function copyLink(postId) {
    navigator.clipboard.writeText(location.origin + '/?page=post&id=' + postId);
    Toast.success('Đã sao chép link!');
}

document.getElementById('load-more-comments')?.addEventListener('click', () => loadComments());
document.getElementById('comment-input')?.addEventListener('keydown', e => {
    if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); submitComment(); }
});

loadComments();
</script>
