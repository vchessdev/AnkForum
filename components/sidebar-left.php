<?php
// components/sidebar-left.php
$user = currentUser();
$currentPage = $_GET['page'] ?? 'home';
?>
<div class="w-full h-full overflow-y-auto px-3 py-4 space-y-1">

    <?php if ($user): ?>
    <!-- User mini profile -->
    <div class="glass-card p-4 mb-4">
        <a href="/?page=profile&u=<?php echo urlencode($user['username']); ?>" class="flex items-center gap-3 group">
            <div class="relative flex-shrink-0">
                <img src="<?php echo avatarUrl($user['avatar']); ?>"
                    alt="<?php echo sanitize($user['username']); ?>"
                    class="avatar w-12 h-12">
                <span class="online-dot"></span>
            </div>
            <div class="min-w-0">
                <p class="font-semibold text-sm text-white group-hover:text-brand-300 transition-colors truncate">
                    <?php echo sanitize($user['username']); ?>
                </p>
                <?php
                $lvl = $user['level'];
                $lvlInfo = getLevelInfo($lvl);
                $pts = $user['points'] ?? 0;
                $progress = getPointsForNextLevel($pts);
                ?>
                <div class="flex items-center gap-1.5 mt-0.5">
                    <span class="level-badge" style="background:<?php echo $lvlInfo['color']; ?>20;border-color:<?php echo $lvlInfo['color']; ?>40;color:<?php echo $lvlInfo['color']; ?>">
                        <?php echo $lvlInfo['icon']; ?> Lv.<?php echo $lvl; ?> <?php echo $lvlInfo['name']; ?>
                    </span>
                </div>
            </div>
        </a>

        <!-- Level progress -->
        <?php if ($lvl < 10): ?>
        <div class="mt-3">
            <div class="progress-bar">
                <div class="progress-fill" style="width:<?php echo $progress['percent']; ?>%;background:linear-gradient(90deg,<?php echo $lvlInfo['color']; ?>,<?php echo $lvlInfo['color']; ?>88);"></div>
            </div>
            <div class="flex justify-between text-xs text-slate-500 mt-1">
                <span><?php echo formatNumber($pts); ?> điểm</span>
                <span>Còn <?php echo formatNumber($progress['needed']); ?> → Lv.<?php echo $lvl+1; ?></span>
            </div>
        </div>
        <?php else: ?>
        <p class="text-xs text-center mt-2" style="color:#eab308">🔱 Đã đạt cấp độ tối đa!</p>
        <?php endif; ?>

        <!-- Stats -->
        <div class="grid grid-cols-3 gap-2 mt-3 pt-3 border-t border-white/5">
            <a href="/?page=profile&u=<?php echo urlencode($user['username']); ?>" class="text-center hover:bg-white/5 rounded-lg p-1.5 transition-colors">
                <p class="text-sm font-bold text-white"><?php echo formatNumber(count($user['followers'] ?? [])); ?></p>
                <p class="text-xs text-slate-500">Theo dõi</p>
            </a>
            <a href="/?page=profile&u=<?php echo urlencode($user['username']); ?>&tab=following" class="text-center hover:bg-white/5 rounded-lg p-1.5 transition-colors">
                <p class="text-sm font-bold text-white"><?php echo formatNumber(count($user['following'] ?? [])); ?></p>
                <p class="text-xs text-slate-500">Đang theo</p>
            </a>
            <?php
            $posts = readJson(POSTS_FILE);
            $postCount = count(array_filter($posts, fn($p) => $p['user_id'] === $user['id']));
            ?>
            <div class="text-center p-1.5">
                <p class="text-sm font-bold text-white"><?php echo formatNumber($postCount); ?></p>
                <p class="text-xs text-slate-500">Bài viết</p>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Navigation -->
    <nav class="space-y-0.5">
        <a href="/" class="sidebar-link <?php echo $currentPage==='home'?'active':''; ?>">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="<?php echo $currentPage==='home'?'#6366f1':'none'; ?>" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            <span class="label">Trang chủ</span>
        </a>

        <?php if ($user): ?>
        <a href="/?page=notifications" class="sidebar-link <?php echo $currentPage==='notifications'?'active':''; ?> relative">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
            <span class="label">Thông báo</span>
            <span class="notif-count-badge notif-badge ml-auto hidden" style="font-size:9px;min-width:16px;height:16px;"></span>
        </a>
        <a href="/?page=profile&u=<?php echo urlencode($user['username']); ?>" class="sidebar-link <?php echo $currentPage==='profile'?'active':''; ?>">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            <span class="label">Trang cá nhân</span>
        </a>
        <a href="/?page=settings" class="sidebar-link <?php echo $currentPage==='settings'?'active':''; ?>">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
            <span class="label">Cài đặt</span>
        </a>
        <?php else: ?>
        <a href="/?page=login" class="sidebar-link">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
            <span class="label">Đăng nhập</span>
        </a>
        <a href="/?page=register" class="sidebar-link">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg>
            <span class="label">Đăng ký</span>
        </a>
        <?php endif; ?>
    </nav>

    <!-- Who to follow -->
    <?php if ($user):
        $allUsers = readJson(USERS_FILE);
        $suggestions = array_filter($allUsers, fn($u) =>
            $u['id'] !== $user['id']
            && !in_array($u['id'], $user['following'] ?? [])
        );
        $suggestions = array_slice(array_values($suggestions), 0, 4);
        if (!empty($suggestions)):
    ?>
    <div class="glass-card p-4 mt-2">
        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Gợi ý theo dõi</h3>
        <div class="space-y-3">
            <?php foreach ($suggestions as $sug):
                $sugLvl = getLevelInfo($sug['level'] ?? 1);
            ?>
            <div class="flex items-center gap-2.5 group">
                <a href="/?page=profile&u=<?php echo urlencode($sug['username']); ?>" class="flex-shrink-0">
                    <img src="<?php echo avatarUrl($sug['avatar']); ?>"
                        alt="<?php echo sanitize($sug['username']); ?>"
                        class="avatar w-8 h-8">
                </a>
                <a href="/?page=profile&u=<?php echo urlencode($sug['username']); ?>" class="flex-1 min-w-0">
                    <p class="text-xs font-semibold text-white truncate group-hover:text-brand-300 transition-colors">
                        <?php echo sanitize($sug['username']); ?>
                    </p>
                    <p class="text-xs" style="color:<?php echo $sugLvl['color']; ?>"><?php echo $sugLvl['icon']; ?> <?php echo $sugLvl['name']; ?></p>
                </a>
                <button onclick="followUser('<?php echo $sug['id']; ?>', this)"
                    class="text-xs btn-primary px-2.5 py-1 rounded-full" style="font-size:11px;padding:4px 10px;">
                    Theo dõi
                </button>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; endif; ?>

</div>

<script>
async function followUser(userId, btn) {
    try {
        const res = await API.post('/api/users/follow.php', { user_id: userId });
        if (res.following) {
            btn.textContent = 'Đang theo';
            btn.className = 'text-xs btn-ghost px-2.5 py-1 rounded-full border border-white/10';
        }
        Toast.success(res.message);
    } catch(e) {
        Toast.error('Vui lòng đăng nhập để theo dõi');
    }
}
</script>
