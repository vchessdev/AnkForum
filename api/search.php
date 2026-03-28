<?php
// api/search.php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../helpers.php';
session_start();

$q = trim($_GET['q'] ?? '');
if (strlen($q) < 2) jsonResponse(['users' => [], 'posts' => []]);

$q_lower = strtolower($q);

// Search users
$allUsers = readJson(USERS_FILE);
$users = [];
foreach ($allUsers as $u) {
    if (stripos($u['username'], $q) !== false || stripos($u['bio'] ?? '', $q) !== false) {
        $lvl = getLevelInfo($u['level'] ?? 1);
        $users[] = [
            'id'         => $u['id'],
            'username'   => $u['username'],
            'avatar_url' => avatarUrl($u['avatar']),
            'level'      => $u['level'] ?? 1,
            'level_info' => $lvl,
        ];
        if (count($users) >= 5) break;
    }
}

// Search posts
$allPosts = readJson(POSTS_FILE);
$posts = [];
foreach ($allPosts as $p) {
    if (!empty($p['content']) && stripos($p['content'], $q) !== false) {
        $posts[] = [
            'id'              => $p['id'],
            'content_preview' => truncate(strip_tags($p['content']), 80),
        ];
        if (count($posts) >= 5) break;
    }
}

jsonResponse(['users' => $users, 'posts' => $posts]);
