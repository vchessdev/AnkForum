<?php
// api/notifications/mark-read.php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../helpers.php';
session_start();
requireLogin();

$user = currentUser();
$all  = readJson(NOTIFICATIONS_FILE);
$updated = false;

foreach ($all as &$n) {
    if ($n['user_id'] === $user['id'] && !$n['read']) {
        $n['read'] = true;
        $updated   = true;
    }
}
unset($n);

if ($updated) writeJson(NOTIFICATIONS_FILE, $all);
jsonResponse(['success' => true]);
