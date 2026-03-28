<?php
// api/comments/list.php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../helpers.php';
session_start();

$postId = $_GET['post_id'] ?? '';
$page   = max(1, intval($_GET['page'] ?? 1));
$limit  = COMMENTS_PER_PAGE;
$user   = currentUser();

if (empty($postId)) jsonResponse(['error' => 'Thiếu post_id'], 422);

$all = readJson(COMMENTS_FILE);

// Get root comments for this post
$roots = array_values(array_filter($all, fn($c) =>
    $c['post_id'] === $postId && empty($c['parent_id'])
));
usort($roots, fn($a,$b) => strtotime($a['created_at']) - strtotime($b['created_at']));

$total = count($roots);
$slice = array_slice($roots, ($page-1)*$limit, $limit);

// Get replies keyed by parent_id
$replies = [];
foreach ($all as $c) {
    if ($c['post_id'] === $postId && !empty($c['parent_id'])) {
        $replies[$c['parent_id']][] = $c;
    }
}

function buildComment(array $c, array $replies, ?array $user): array {
    $author = getUserById($c['user_id']);
    
    // Handle deleted user
    if (!$author) {
        $author = [
            'id'     => $c['user_id'],
            'username' => '[Người dùng đã xóa]',
            'avatar' => 'assets/images/default-avatar.svg',
            'level'  => 1,
        ];
    }
    
    $lvlInfo = getLevelInfo($author['level'] ?? 1);
    $childReplies = [];
    foreach ($replies[$c['id']] ?? [] as $r) {
        $childReplies[] = buildComment($r, $replies, $user);
    }
    return [
        'id'         => $c['id'],
        'content'    => $c['content'],
        'likes'      => $c['likes'] ?? [],
        'liked'      => $user ? in_array($user['id'], $c['likes'] ?? []) : false,
        'like_count' => count($c['likes'] ?? []),
        'time_ago'   => timeAgo($c['created_at']),
        'created_at' => $c['created_at'],
        'is_owner'   => $user && $user['id'] === $c['user_id'],
        'replies'    => $childReplies,
        'author' => [
            'id'         => $author['id'],
            'username'   => $author['username'],
            'avatar'     => avatarUrl($author['avatar']),
            'level'      => $author['level'],
            'level_info' => $lvlInfo,
        ],
    ];
}

$result = array_map(fn($c) => buildComment($c, $replies, $user), $slice);

jsonResponse([
    'comments' => $result,
    'total'    => $total,
    'page'     => $page,
    'has_more' => ($page * $limit) < $total,
]);
