<?php
// api/posts/like.php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../helpers.php';
session_start();
requireLogin();

$input   = json_decode(file_get_contents('php://input'), true);
$postId  = $input['post_id'] ?? '';
$user    = currentUser();

$posts = readJson(POSTS_FILE);
$found = false;
foreach ($posts as &$post) {
    if ($post['id'] !== $postId) continue;
    $found    = true;
    $likes    = $post['likes'] ?? [];
    $liked    = in_array($user['id'], $likes);

    if ($liked) {
        $post['likes'] = array_values(array_filter($likes, fn($id) => $id !== $user['id']));
    } else {
        $post['likes'][] = $user['id'];
        // Notify post author
        if ($post['user_id'] !== $user['id']) {
            addNotification($post['user_id'], 'like', [
                'from_user_id'  => $user['id'],
                'from_username' => $user['username'],
                'from_avatar'   => $user['avatar'],
                'post_id'       => $postId,
                'post_preview'  => truncate($post['content'] ?? '', 50),
            ]);
            // Points for author
            $author = getUserById($post['user_id']);
            if ($author) {
                $pts = ($author['points'] ?? 0) + 2;
                updateUser($author['id'], ['points' => $pts, 'level' => calculateLevel($pts)]);
            }
        }
    }
    $newLiked = !$liked;
    $newCount = count($post['likes']);
    break;
}
unset($post);

if (!$found) jsonResponse(['error' => 'Bài viết không tồn tại'], 404);

writeJson(POSTS_FILE, $posts);
jsonResponse(['liked' => $newLiked, 'count' => $newCount]);
