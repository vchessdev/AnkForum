<?php
// ============================================================
// AnkForum - api/livestreams/end.php
// End a livestream
// ============================================================

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../helpers.php';

session_start();
requireLogin();

$user = currentUser();
$data = json_decode(file_get_contents('php://input'), true) ?? $_POST;

if (empty($data['stream_id'])) {
    jsonResponse(['error' => 'Stream ID không được để trống'], 400);
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

$stream = $livestreams[$streamIndex];

// Verify ownership
if ($stream['author_id'] !== $user['id']) {
    jsonResponse(['error' => 'Chỉ tác giả mới có thể kết thúc stream'], 403);
}

$stream['status'] = 'ended';
$stream['ended_at'] = date('c');
$stream['duration'] = strtotime($stream['ended_at']) - strtotime($stream['started_at']);

$livestreams[$streamIndex] = $stream;
writeJson(LIVESTREAMS_FILE, $livestreams);

jsonResponse([
    'success' => true,
    'message' => 'Kết thúc phát trực tiếp',
    'stream' => $stream
]);
