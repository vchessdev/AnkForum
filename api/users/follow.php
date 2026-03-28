<?php
// api/users/follow.php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../helpers.php';
session_start();
requireLogin();

$input      = json_decode(file_get_contents('php://input'), true);
$targetId   = $input['user_id'] ?? '';
$currentUser = currentUser();

if ($targetId === $currentUser['id']) jsonResponse(['error' => 'Không thể tự theo dõi'], 422);

$target = getUserById($targetId);
if (!$target) jsonResponse(['error' => 'Người dùng không tồn tại'], 404);

$users = readJson(USERS_FILE);
$isFollowing = in_array($targetId, $currentUser['following'] ?? []);

foreach ($users as &$u) {
    if ($u['id'] === $currentUser['id']) {
        $following = $u['following'] ?? [];
        if ($isFollowing) {
            $u['following'] = array_values(array_filter($following, fn($id) => $id !== $targetId));
        } else {
            $u['following'][] = $targetId;
        }
    }
    if ($u['id'] === $targetId) {
        $followers = $u['followers'] ?? [];
        if ($isFollowing) {
            $u['followers'] = array_values(array_filter($followers, fn($id) => $id !== $currentUser['id']));
        } else {
            $u['followers'][] = $currentUser['id'];
            // Points for target
            $pts = ($u['points'] ?? 0) + 3;
            $u['points'] = $pts;
            $u['level'] = calculateLevel($pts);
        }
    }
}
unset($u);
writeJson(USERS_FILE, $users);

if (!$isFollowing) {
    addNotification($targetId, 'follow', [
        'from_user_id'  => $currentUser['id'],
        'from_username' => $currentUser['username'],
        'from_avatar'   => $currentUser['avatar'],
    ]);
}

$newTarget = getUserById($targetId);
jsonResponse([
    'following'       => !$isFollowing,
    'follower_count'  => count($newTarget['followers'] ?? []),
    'message'         => !$isFollowing
        ? 'Đã theo dõi ' . $target['username']
        : 'Đã bỏ theo dõi ' . $target['username'],
]);
