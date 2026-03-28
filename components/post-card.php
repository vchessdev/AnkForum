<?php
// components/post-card.php
// Variables expected: $post (array), $user (currentUser or null)
// This file is included dynamically - $post must be set before including
if (!isset($post)) return;

$author   = getUserById($post['user_id']);
if (!$author) return;

$likes      = $post['likes'] ?? [];
$likeCount  = count($likes);
$liked      = $user ? in_array($user['id'], $likes) : false;
$isOwner    = $user && $user['id'] === $post['user_id'];
$commentCnt = $post['comment_count'] ?? 0;
$lvlInfo    = getLevelInfo($author['level'] ?? 1);
$mediaFiles = $post['media'] ?? [];
$postId     = $post['id'];
?>
<article class="post-card animate-slide-up" data-post-id="<?php echo $postId; ?>">
    <div class="p-5">
        <!-- Author header -->
        <div class="flex items-start justify-between mb-4">
            <div class="flex items-center gap-3">
                <a href="/?page=profile&u=<?php echo urlencode($author['username']); ?>" class="relative flex-shrink-0">
                    <img src="<?php echo avatarUrl($author['avatar']); ?>"
                        alt="<?php echo sanitize($author['username']); ?>"
                        class="avatar w-11 h-11">
                    <?php if ($author['is_online'] ?? false): ?>
                    <span class="online-dot"></span>
                    <?php endif; ?>
                </a>
                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <a href="/?page=profile&u=<?php echo urlencode($author['username']); ?>"
                            class="font-bold text-sm text-white hover:text-brand-300 transition-colors">
                            <?php echo sanitize($author['username']); ?>
                        </a>
                        <span class="level-badge" style="background:<?php echo $lvlInfo['color']; ?>18;border-color:<?php echo $lvlInfo['color']; ?>35;color:<?php echo $lvlInfo['color']; ?>;font-size:10px;">
                            <?php echo $lvlInfo['icon']; ?> Lv.<?php echo $author['level'] ?? 1; ?>
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 mt-0.5" title="<?php echo formatDate($post['created_at']); ?>">
                        <?php echo timeAgo($post['created_at']); ?>
                    </p>
                </div>
            </div>

            <!-- Post actions menu -->
            <div class="relative">
                <button data-dropdown="post-menu-<?php echo $postId; ?>" class="btn-ghost p-2 rounded-lg">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="5" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="12" cy="19" r="1.5"/></svg>
                </button>
                <div id="post-menu-<?php echo $postId; ?>" class="dropdown-menu absolute right-0 top-full hidden" style="min-width:160px;">
                    <a href="/?page=post&id=<?php echo $postId; ?>" class="dropdown-item">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        Xem bài viết
                    </a>
                    <button onclick="copyLink('<?php echo $postId; ?>')" class="dropdown-item w-full text-left">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                        Sao chép link
                    </button>
                    <?php if ($isOwner): ?>
                    <div class="border-t border-white/5 mt-1 pt-1">
                        <button onclick="deletePost('<?php echo $postId; ?>')" class="dropdown-item danger w-full text-left">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                            Xóa bài viết
                        </button>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Content -->
        <?php if (!empty($post['content'])): ?>
        <div class="text-sm text-slate-200 leading-relaxed mb-4 whitespace-pre-line">
            <?php echo nl2br(sanitize($post['content'])); ?>
        </div>
        <?php endif; ?>

        <!-- Media grid -->
        <?php if (!empty($mediaFiles)): ?>
        <div class="post-media mb-4">
            <div class="post-media-grid count-<?php echo min(count($mediaFiles), 4); ?>">
                <?php foreach (array_slice($mediaFiles, 0, 4) as $idx => $media):
                    $isImage = in_array($media['mime'] ?? '', ALLOWED_IMAGE_TYPES);
                    $isVideo = in_array($media['mime'] ?? '', ALLOWED_VIDEO_TYPES);
                    $isLast  = $idx === 3 && count($mediaFiles) > 4;
                ?>
                <div class="relative overflow-hidden <?php echo $idx===0&&count($mediaFiles)===3?'row-span-2':''; ?>" style="max-height:350px;border-radius:10px;">
                    <?php if ($isImage): ?>
                    <img src="<?php echo $media['path']; ?>" alt="Media"
                        style="width:100%;height:100%;object-fit:cover;cursor:zoom-in;"
                        onclick="Lightbox.open(this.src)">
                    <?php elseif ($isVideo): ?>
                    <video controls style="width:100%;height:100%;object-fit:cover;border-radius:10px;" preload="metadata">
                        <source src="<?php echo $media['path']; ?>">
                    </video>
                    <?php else: ?>
                    <a href="<?php echo $media['path']; ?>" target="_blank"
                        class="flex items-center gap-2 p-3 bg-white/5 rounded-xl text-sm text-brand-400 hover:bg-white/10">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                        Tải file
                    </a>
                    <?php endif; ?>
                    <?php if ($isLast): ?>
                    <div onclick="location.href='/?page=post&id=<?php echo $postId; ?>'"
                        class="absolute inset-0 bg-black/70 flex items-center justify-center cursor-pointer rounded-xl">
                        <span class="text-2xl font-black text-white">+<?php echo count($mediaFiles)-4; ?></span>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Action buttons -->
        <div class="flex items-center gap-2 pt-3 border-t border-white/5">
            <!-- Like -->
            <button onclick="toggleLike('<?php echo $postId; ?>', this)"
                class="like-btn <?php echo $liked ? 'liked' : ''; ?>"
                data-post-id="<?php echo $postId; ?>">
                <svg width="15" height="15" viewBox="0 0 24 24"
                    fill="<?php echo $liked ? 'currentColor' : 'none'; ?>"
                    stroke="currentColor" stroke-width="2">
                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                </svg>
                <span class="like-count"><?php echo formatNumber($likeCount); ?></span>
            </button>

            <!-- Comment -->
            <a href="/?page=post&id=<?php echo $postId; ?>#comments" class="like-btn hover:bg-brand-500/10 hover:text-brand-400 hover:border-brand-500/20">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                </svg>
                <span><?php echo formatNumber($commentCnt); ?></span>
            </a>

            <!-- Share -->
            <button onclick="copyLink('<?php echo $postId; ?>')" class="like-btn hover:bg-cyan-500/10 hover:text-cyan-400 hover:border-cyan-500/20">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/>
                    <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/>
                </svg>
            </button>
        </div>
    </div>
</article>
