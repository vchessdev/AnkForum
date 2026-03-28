<?php
// api/comments/add.php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../helpers.php';
session_start();
requireLogin();

$input    = json_decode(file_get_contents('php://input'), true);
$postId   = $input['post_id'] ?? '';
$content  = trim($input['content'] ?? '');
$parentId = $input['parent_id'] ?? null;
$user     = currentUser();

if (empty($postId) || empty($content)) jsonResponse(['error' => 'Thiếu thông tin'], 422);
if (mb_strlen($content) > 1000) jsonResponse(['error' => 'Bình luận tối đa 1000 ký tự'], 422);

// Verify post exists
$posts = readJson(POSTS_FILE);
$post  = null;
foreach ($posts as &$p) {
    if ($p['id'] === $postId) { $post = &$p; break; }
}
if (!$post) jsonResponse(['error' => 'Bài viết không tồn tại'], 404);

// Verify parent comment if replying
if ($parentId) {
    $comments = readJson(COMMENTS_FILE);
    $parentExists = false;
    foreach ($comments as $c) {
        if ($c['id'] === $parentId && $c['post_id'] === $postId) { $parentExists = true; break; }
    }
    if (!$parentExists) jsonResponse(['error' => 'Bình luận gốc không tồn tại'], 404);
} else {
    $comments = readJson(COMMENTS_FILE);
}

$comment = [
    'id'         => generateId('c'),
    'post_id'    => $postId,
    'user_id'    => $user['id'],
    'parent_id'  => $parentId,
    'content'    => sanitize($content),
    'likes'      => [],
    'created_at' => date('Y-m-d H:i:s'),
];

$comments[] = $comment;
writeJson(COMMENTS_FILE, $comments);

// Update post comment count
$post['comment_count'] = ($post['comment_count'] ?? 0) + 1;
writeJson(POSTS_FILE, $posts);

// Award points to commenter
$pts = ($user['points'] ?? 0) + 5;
updateUser($user['id'], ['points' => $pts, 'level' => calculateLevel($pts)]);

// Notify post author
if ($post['user_id'] !== $user['id']) {
    addNotification($post['user_id'], 'comment', [
        'from_user_id'  => $user['id'],
        'from_username' => $user['username'],
        'from_avatar'   => $user['avatar'],
        'post_id'       => $postId,
        'comment_id'    => $comment['id'],
        'preview'       => truncate($content, 60),
    ]);
}

// Notify parent comment author if replying
if ($parentId) {
    $parentComment = null;
    foreach ($comments as $c) {
        if ($c['id'] === $parentId) { $parentComment = $c; break; }
    }
    if ($parentComment && $parentComment['user_id'] !== $user['id']) {
        addNotification($parentComment['user_id'], 'reply', [
            'from_user_id'  => $user['id'],
            'from_username' => $user['username'],
            'from_avatar'   => $user['avatar'],
            'post_id'       => $postId,
            'comment_id'    => $comment['id'],
            'preview'       => truncate($content, 60),
        ]);
    }
}

$lvlInfo = getLevelInfo($user['level'] ?? 1);
jsonResponse([
    'success' => true,
    'comment' => [
        'id'         => $comment['id'],
        'content'    => $comment['content'],
        'likes'      => [],
        'liked'      => false,
        'like_count' => 0,
        'time_ago'   => 'Vừa xong',
        'is_owner'   => true,
        'replies'    => [],
        'author' => [
            'id'         => $user['id'],
            'username'   => $user['username'],
            'avatar'     => avatarUrl($user['avatar']),
            'level'      => $user['level'],
            'level_info' => $lvlInfo,
        ],
    ],
]);
