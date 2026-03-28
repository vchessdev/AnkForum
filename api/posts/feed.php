<?php
// api/posts/feed.php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../helpers.php';
session_start();

$page   = max(1, intval($_GET['page'] ?? 1));
$limit  = POSTS_PER_PAGE;
$offset = ($page - 1) * $limit;
$user   = currentUser();

$posts = readJson(POSTS_FILE);

// Sort by newest
usort($posts, fn($a,$b) => strtotime($b['created_at']) - strtotime($a['created_at']));

$total = count($posts);
$slice = array_slice($posts, $offset, $limit);

$result = [];
foreach ($slice as $post) {
    $author = getUserById($post['user_id']);
    
    // Handle deleted user: show post with placeholder author info
    if (!$author) {
        $author = [
            'id'        => $post['user_id'],
            'username'  => '[Người dùng đã xóa]',
            'avatar'    => 'assets/images/default-avatar.svg',
            'level'     => 1,
        ];
    }

    $likes    = $post['likes'] ?? [];
    $liked    = $user ? in_array($user['id'], $likes) : false;
    $lvlInfo  = getLevelInfo($author['level'] ?? 1);

    $result[] = [
        'id'           => $post['id'],
        'content'      => $post['content'],
        'media'        => $post['media'] ?? [],
        'like_count'   => count($likes),
        'liked'        => $liked,
        'comment_count'=> $post['comment_count'] ?? 0,
        'created_at'   => $post['created_at'],
        'time_ago'     => timeAgo($post['created_at']),
        'author' => [
            'id'        => $author['id'],
            'username'  => $author['username'],
            'avatar'    => avatarUrl($author['avatar']),
            'level'     => $author['level'] ?? 1,
            'level_info'=> $lvlInfo,
        ],
    ];
}

jsonResponse([
    'posts'    => $result,
    'page'     => $page,
    'total'    => $total,
    'has_more' => ($offset + $limit) < $total,
]);
