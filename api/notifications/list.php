<?php
// api/notifications/list.php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../helpers.php';
session_start();
requireLogin();

$user   = currentUser();
$page   = max(1, intval($_GET['page'] ?? 1));
$limit  = NOTIFICATIONS_PER_PAGE;
$offset = ($page - 1) * $limit;

$all = readJson(NOTIFICATIONS_FILE);

// Filter for this user, newest first
$mine = array_values(array_filter($all, fn($n) => $n['user_id'] === $user['id']));
usort($mine, fn($a,$b) => strtotime($b['created_at']) - strtotime($a['created_at']));

$total  = count($mine);
$slice  = array_slice($mine, $offset, $limit);

// Mark as read
$updated = false;
foreach ($all as &$n) {
    if ($n['user_id'] === $user['id'] && !$n['read']) {
        $n['read'] = true;
        $updated = true;
    }
}
unset($n);
if ($updated) writeJson(NOTIFICATIONS_FILE, $all);

$result = array_map(fn($n) => [
    'id'         => $n['id'],
    'type'       => $n['type'],
    'data'       => $n['data'],
    'read'       => $n['read'],
    'time_ago'   => timeAgo($n['created_at']),
    'created_at' => $n['created_at'],
], $slice);

jsonResponse([
    'notifications' => $result,
    'total'   => $total,
    'page'    => $page,
    'has_more'=> ($offset + $limit) < $total,
]);
