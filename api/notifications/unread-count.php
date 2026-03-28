<?php
// api/notifications/unread-count.php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../helpers.php';
session_start();
requireLogin();

$user  = currentUser();
$all   = readJson(NOTIFICATIONS_FILE);
$count = 0;
foreach ($all as $n) {
    if ($n['user_id'] === $user['id'] && !$n['read']) $count++;
}
jsonResponse(['count' => $count]);
