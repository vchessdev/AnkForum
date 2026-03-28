<?php
// api/comments/like.php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../helpers.php';
session_start();
requireLogin();

$input     = json_decode(file_get_contents('php://input'), true);
$commentId = $input['comment_id'] ?? '';
$user      = currentUser();

$comments = readJson(COMMENTS_FILE);
$found = false;
foreach ($comments as &$c) {
    if ($c['id'] !== $commentId) continue;
    $found = true;
    $likes = $c['likes'] ?? [];
    $liked = in_array($user['id'], $likes);
    if ($liked) {
        $c['likes'] = array_values(array_filter($likes, fn($id) => $id !== $user['id']));
    } else {
        $c['likes'][] = $user['id'];
    }
    $newLiked = !$liked;
    $newCount = count($c['likes']);
    break;
}
unset($c);

if (!$found) jsonResponse(['error' => 'Không tìm thấy bình luận'], 404);
writeJson(COMMENTS_FILE, $comments);
jsonResponse(['liked' => $newLiked, 'count' => $newCount]);
