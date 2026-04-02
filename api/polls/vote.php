<?php
// ============================================================
// AnkForum - api/polls/vote.php
// Vote on a poll option
// ============================================================

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../helpers.php';

session_start();
requireLogin();

$user = currentUser();
$data = json_decode(file_get_contents('php://input'), true) ?? $_POST;

if (empty($data['poll_id']) || empty($data['option_id'])) {
    jsonResponse(['error' => 'Poll ID và Option ID không được để trống'], 400);
}

if (!verifyCsrf($data['csrf_token'] ?? '')) {
    jsonResponse(['error' => 'CSRF token invalid'], 403);
}

$polls = readJson(POLLS_FILE);
$pollIndex = null;
$poll = null;

foreach ($polls as $i => $p) {
    if ($p['id'] === $data['poll_id']) {
        $pollIndex = $i;
        $poll = $p;
        break;
    }
}

if (!$poll) {
    jsonResponse(['error' => 'Poll không tồn tại'], 404);
}

// Check if user already voted (unless multiple_choice)
if (!$poll['multiple_choice']) {
    foreach ($poll['voters'] as $voter) {
        if ($voter['user_id'] === $user['id']) {
            jsonResponse(['error' => 'Bạn đã bình chọn rồi'], 400);
        }
    }
}

// Find and update option
$optionFound = false;
foreach ($poll['options'] as &$opt) {
    if ($opt['id'] === $data['option_id']) {
        $opt['votes']++;
        $optionFound = true;
        break;
    }
}

if (!$optionFound) {
    jsonResponse(['error' => 'Lựa chọn không tồn tại'], 404);
}

// Record voter
$poll['voters'][] = [
    'user_id' => $user['id'],
    'username' => $user['username'],
    'voted_at' => date('c')
];

$poll['updated_at'] = date('c');
$polls[$pollIndex] = $poll;
writeJson(POLLS_FILE, $polls);

jsonResponse([
    'success' => true,
    'message' => 'Bình chọn thành công',
    'poll' => $poll
]);
