<?php
// components/sidebar-right.php
$posts = readJson(POSTS_FILE);
$users = readJson(USERS_FILE);

// Trending: top liked posts in last 7 days
$recent = array_filter($posts, fn($p) => strtotime($p['created_at']) > strtotime('-7 days'));
usort($recent, fn($a,$b) => count($b['likes']??[]) - count($a['likes']??[]));
$trending = array_slice(array_values($recent), 0, 5);

// Top active users
usort($users, fn($a,$b) => ($b['points']??0) - ($a['points']??0));
$topUsers = array_slice($users, 0, 5);
?>
<div class="w-full h-full overflow-y-auto px-3 py-4 space-y-4">

    <!-- User Profile Card (when logged in) -->
    <?php $user = currentUser(); ?>
    <?php if ($user): ?>
    <div class="glass-card p-4 bg-gradient-to-br from-brand-500/10 to-purple-500/10 border border-brand-500/20">
        <div class="flex items-center gap-3 mb-4">
            <img src="<?php echo avatarUrl($user['avatar']); ?>"
                 alt="<?php echo sanitize($user['username']); ?>"
                 class="avatar w-12 h-12">
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-white truncate"><?php echo sanitize($user['username']); ?></p>
                <?php $lvl = getLevelInfo($user['level'] ?? 1); ?>
                <p class="text-xs" style="color:<?php echo $lvl['color']; ?>"><?php echo $lvl['icon']; ?> Lv.<?php echo $user['level']; ?></p>
            </div>
        </div>
        <div class="grid grid-cols-3 gap-2 mb-3 text-center">
            <div class="bg-slate-700/50 rounded p-2">
                <p class="text-xs text-slate-400">Điểm</p>
                <p class="text-sm font-bold text-white"><?php echo number_format($user['points'] ?? 0); ?></p>
            </div>
            <div class="bg-slate-700/50 rounded p-2">
                <p class="text-xs text-slate-400">Bài viết</p>
                <p class="text-sm font-bold text-white"><?php echo count(array_filter(readJson(POSTS_FILE), fn($p) => $p['user_id'] === $user['id'])); ?></p>
            </div>
            <div class="bg-slate-700/50 rounded p-2">
                <p class="text-xs text-slate-400">Theo dõi</p>
                <p class="text-sm font-bold text-white"><?php echo count($user['following'] ?? []); ?></p>
            </div>
        </div>
        <a href="/?page=profile&u=<?php echo urlencode($user['username']); ?>"
           class="block w-full py-2 px-3 bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold rounded text-center transition">
            Xem Profile
        </a>
    </div>
    <?php endif; ?>

    <!-- Online users -->
    <?php
    $onlineUsers = array_filter($users, fn($u) =>
        $u['is_online'] ?? false &&
        strtotime($u['last_seen']??'') > time() - 300
    );
    $onlineUsers = array_slice(array_values($onlineUsers), 0, 8);
    if (!empty($onlineUsers)):
    ?>
    <div class="glass-card p-4">
        <div class="flex items-center gap-2 mb-3">
            <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Đang online (<?php echo count($onlineUsers); ?>)</h3>
        </div>
        <div class="flex flex-wrap gap-2">
            <?php foreach ($onlineUsers as $ou): ?>
            <a href="/?page=profile&u=<?php echo urlencode($ou['username']); ?>" title="<?php echo sanitize($ou['username']); ?>">
                <div class="relative">
                    <img src="<?php echo avatarUrl($ou['avatar']); ?>"
                        alt="<?php echo sanitize($ou['username']); ?>"
                        class="avatar w-9 h-9">
                    <span class="online-dot" style="width:8px;height:8px;"></span>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Trending posts -->
    <?php if (!empty($trending)): ?>
    <div class="glass-card p-4">
        <div class="flex items-center gap-2 mb-3">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Nổi bật tuần này</h3>
        </div>
        <div class="space-y-3">
            <?php foreach ($trending as $i => $tp):
                $tpUser = getUserById($tp['user_id']);
                $likeCount = count($tp['likes'] ?? []);
                $preview = truncate(strip_tags($tp['content'] ?? ''), 60);
            ?>
            <a href="/?page=post&id=<?php echo $tp['id']; ?>" class="block group">
                <div class="flex items-start gap-2.5">
                    <span class="text-lg font-black" style="color:<?php echo ['#f59e0b','#94a3b8','#cd7c2f','#6366f1','#64748b'][$i]; ?>">
                        <?php echo $i+1; ?>
                    </span>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs text-slate-300 truncate-2 group-hover:text-white transition-colors leading-relaxed">
                            <?php echo sanitize($preview) ?: '[Media post]'; ?>
                        </p>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="text-xs text-slate-600">@<?php echo sanitize($tpUser['username'] ?? '?'); ?></span>
                            <span class="text-xs text-red-400">♥ <?php echo formatNumber($likeCount); ?></span>
                        </div>
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Top members -->
    <?php if (!empty($topUsers)): ?>
    <div class="glass-card p-4">
        <div class="flex items-center gap-2 mb-3">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#a78bfa" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Thành viên nổi bật</h3>
        </div>
        <div class="space-y-2.5">
            <?php foreach ($topUsers as $i => $tu):
                $tuLvl = getLevelInfo($tu['level'] ?? 1);
                $medals = ['🥇','🥈','🥉','4️⃣','5️⃣'];
            ?>
            <a href="/?page=profile&u=<?php echo urlencode($tu['username']); ?>" class="flex items-center gap-2.5 group">
                <span class="text-sm w-5 text-center"><?php echo $medals[$i]; ?></span>
                <img src="<?php echo avatarUrl($tu['avatar']); ?>"
                    alt="<?php echo sanitize($tu['username']); ?>"
                    class="avatar w-7 h-7 flex-shrink-0">
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-semibold text-slate-200 truncate group-hover:text-brand-300 transition-colors">
                        <?php echo sanitize($tu['username']); ?>
                    </p>
                    <p class="text-xs" style="color:<?php echo $tuLvl['color']; ?>"><?php echo $tuLvl['icon']; ?> <?php echo formatNumber($tu['points']??0); ?> điểm</p>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Footer links -->
    <div class="px-1">
        <p class="text-xs text-slate-600 leading-relaxed">
            <a href="#" class="hover:text-slate-400 transition-colors">Điều khoản</a> ·
            <a href="#" class="hover:text-slate-400 transition-colors">Chính sách</a> ·
            <a href="#" class="hover:text-slate-400 transition-colors">Liên hệ</a>
        </p>
        <p class="text-xs text-slate-700 mt-1">© <?php echo date('Y'); ?> AnkForum</p>
    </div>
</div>
