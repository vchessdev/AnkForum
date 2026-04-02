<?php
// ============================================================
// AnkForum - api/livestreams/comment.php
// Add comment to livestream
// ============================================================

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../helpers.php';

session_start();
requireLogin();

$user = currentUser();
$data = json_decode(file_get_contents('php://input'), true) ?? $_POST;

if (empty($data['stream_id']) || empty($data['text'])) {
    jsonResponse(['error' => 'Stream ID và text không được để trống'], 400);
}

if (!verifyCsrf($data['csrf_token'] ?? '')) {
    jsonResponse(['error' => 'CSRF token invalid'], 403);
}

$livestreams = readJson(LIVESTREAMS_FILE);
$streamIndex = null;

foreach ($livestreams as $i => $stream) {
    if ($stream['id'] === $data['stream_id']) {
        $streamIndex = $i;
        break;
    }
}

if ($streamIndex === null) {
    jsonResponse(['error' => 'Stream không tồn tại'], 404);
}

$comment = [
    'id' => generateId('livecomment_'),
    'user_id' => $user['id'],
    'username' => $user['username'],
    'avatar' => $user['avatar'] ?? null,
    'text' => sanitize($data['text']),
    'created_at' => date('c')
];

$livestreams[$streamIndex]['comments'][] = $comment;
writeJson(LIVESTREAMS_FILE, $livestreams);

jsonResponse([
    'success' => true,
    'comment' => $comment
]);
