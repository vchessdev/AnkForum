<?php
// api/users/followers.php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../helpers.php';
session_start();

$username = $_GET['username'] ?? '';
$type     = $_GET['type'] ?? 'followers'; // 'followers' or 'following'

$target = getUserByUsername($username);
if (!$target) jsonResponse(['error' => 'Người dùng không tồn tại'], 404);

$ids = ($type === 'followers') ? ($target['followers'] ?? []) : ($target['following'] ?? []);

$users = [];
foreach ($ids as $id) {
    $u = getUserById($id);
    if (!$u) continue;
    $lvl = getLevelInfo($u['level'] ?? 1);
    $users[] = [
        'id'         => $u['id'],
        'username'   => $u['username'],
        'avatar'     => avatarUrl($u['avatar']),
        'level'      => $u['level'] ?? 1,
        'level_info' => $lvl,
    ];
}

jsonResponse(['users' => $users, 'count' => count($users)]);
