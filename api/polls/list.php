<?php
// ============================================================
// AnkForum - api/polls/list.php
// Get polls (by post or standalone)
// ============================================================

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../helpers.php';

session_start();

$postId = $_GET['post_id'] ?? null;
$standalone = $_GET['standalone'] === '1';

$polls = readJson(POLLS_FILE);
$result = [];

foreach ($polls as $poll) {
    if ($standalone && $poll['post_id'] === null) {
        $result[] = $poll;
    } elseif (!$standalone && $poll['post_id'] === $postId) {
        $result[] = $poll;
    }
}

// Sort by created_at descending
usort($result, function($a, $b) {
    return strtotime($b['created_at']) - strtotime($a['created_at']);
});

jsonResponse(['polls' => $result]);
