<?php
// api/users/profile.php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../helpers.php';
session_start();

$username = $_GET['username'] ?? '';
if (empty($username)) jsonResponse(['error' => 'Thiếu username'], 422);

$target = getUserByUsername($username);
if (!$target) jsonResponse(['error' => 'Người dùng không tồn tại'], 404);

$viewer = currentUser();

// Posts by this user
$allPosts = readJson(POSTS_FILE);
$userPosts = array_values(array_filter($allPosts, fn($p) => $p['user_id'] === $target['id']));
usort($userPosts, fn($a,$b) => strtotime($b['created_at']) - strtotime($a['created_at']));

// Build posts with like info
$page   = max(1, intval($_GET['page'] ?? 1));
$limit  = POSTS_PER_PAGE;
$total  = count($userPosts);
$slice  = array_slice($userPosts, ($page-1)*$limit, $limit);

$posts = [];
foreach ($slice as $post) {
    $likes = $post['likes'] ?? [];
    $lvlInfo = getLevelInfo($target['level'] ?? 1);
    $posts[] = [
        'id'           => $post['id'],
        'content'      => $post['content'],
        'media'        => $post['media'] ?? [],
        'like_count'   => count($likes),
        'liked'        => $viewer ? in_array($viewer['id'], $likes) : false,
        'comment_count'=> $post['comment_count'] ?? 0,
        'created_at'   => $post['created_at'],
        'time_ago'     => timeAgo($post['created_at']),
        'author' => [
            'id'         => $target['id'],
            'username'   => $target['username'],
            'avatar'     => avatarUrl($target['avatar']),
            'level'      => $target['level'] ?? 1,
            'level_info' => $lvlInfo,
        ],
    ];
}

$isFollowing = $viewer ? in_array($target['id'], $viewer['following'] ?? []) : false;
$lvlInfo     = getLevelInfo($target['level'] ?? 1);
$progress    = getPointsForNextLevel($target['points'] ?? 0);

jsonResponse([
    'user' => [
        'id'           => $target['id'],
        'username'     => $target['username'],
        'display_name' => $target['display_name'] ?? $target['username'],
        'bio'          => $target['bio'] ?? '',
        'avatar'       => avatarUrl($target['avatar']),
        'banner'       => bannerUrl($target['banner']),
        'level'        => $target['level'] ?? 1,
        'level_info'   => $lvlInfo,
        'points'       => $target['points'] ?? 0,
        'progress'     => $progress,
        'follower_count'  => count($target['followers'] ?? []),
        'following_count' => count($target['following'] ?? []),
        'is_following' => $isFollowing,
        'is_own'       => $viewer && $viewer['id'] === $target['id'],
        'joined'       => date('d/m/Y', strtotime($target['created_at'])),
    ],
    'posts'    => $posts,
    'total'    => $total,
    'has_more' => ($page * $limit) < $total,
]);
