<?php
// api/posts/delete.php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../helpers.php';
session_start();
requireLogin();

$input  = json_decode(file_get_contents('php://input'), true);
$postId = $input['post_id'] ?? '';
$user   = currentUser();

$posts = readJson(POSTS_FILE);
$newPosts = [];
$deleted = false;

foreach ($posts as $post) {
    if ($post['id'] === $postId) {
        if ($post['user_id'] !== $user['id']) {
            jsonResponse(['error' => 'Không có quyền xóa bài viết này'], 403);
        }
        // Delete associated media files
        foreach ($post['media'] ?? [] as $media) {
            $filePath = ROOT_PATH . '/' . $media['path'];
            if (file_exists($filePath)) @unlink($filePath);
        }
        $deleted = true;
        continue;
    }
    $newPosts[] = $post;
}

if (!$deleted) jsonResponse(['error' => 'Bài viết không tồn tại'], 404);

writeJson(POSTS_FILE, $newPosts);

// Remove related comments
$comments = readJson(COMMENTS_FILE);
$comments = array_values(array_filter($comments, fn($c) => $c['post_id'] !== $postId));
writeJson(COMMENTS_FILE, $comments);

jsonResponse(['success' => true, 'message' => 'Đã xóa bài viết']);
