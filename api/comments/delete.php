<?php
// api/comments/delete.php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../helpers.php';
session_start();
requireLogin();

$input     = json_decode(file_get_contents('php://input'), true);
$commentId = $input['comment_id'] ?? '';
$user      = currentUser();

$comments = readJson(COMMENTS_FILE);
$found = false;
$postId = null;

foreach ($comments as $c) {
    if ($c['id'] === $commentId) {
        if ($c['user_id'] !== $user['id']) jsonResponse(['error' => 'Không có quyền'], 403);
        $found  = true;
        $postId = $c['post_id'];
        break;
    }
}
if (!$found) jsonResponse(['error' => 'Bình luận không tồn tại'], 404);

// Remove comment and its replies
$deleted = 0;
$newComments = array_values(array_filter($comments, function($c) use ($commentId, &$deleted) {
    if ($c['id'] === $commentId || $c['parent_id'] === $commentId) {
        $deleted++;
        return false;
    }
    return true;
}));
writeJson(COMMENTS_FILE, $newComments);

// Update post comment count
if ($postId) {
    $posts = readJson(POSTS_FILE);
    foreach ($posts as &$p) {
        if ($p['id'] === $postId) {
            $p['comment_count'] = max(0, ($p['comment_count'] ?? 0) - $deleted);
            break;
        }
    }
    writeJson(POSTS_FILE, $posts);
}

jsonResponse(['success' => true]);
