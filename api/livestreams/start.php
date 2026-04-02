<?php
// ============================================================
// AnkForum - api/livestreams/start.php
// Start a livestream
// ============================================================

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../helpers.php';

session_start();
requireLogin();

$user = currentUser();
$data = json_decode(file_get_contents('php://input'), true) ?? $_POST;

if (!verifyCsrf($data['csrf_token'] ?? '')) {
    jsonResponse(['error' => 'CSRF token invalid'], 403);
}

// Check if user already has active stream
$livestreams = readJson(LIVESTREAMS_FILE);
foreach ($livestreams as $stream) {
    if ($stream['author_id'] === $user['id'] && $stream['status'] === 'live') {
        jsonResponse(['error' => 'Bạn đã có 1 stream đang phát'], 400);
    }
}

$stream = [
    'id' => generateId('stream_'),
    'title' => sanitize($data['title'] ?? 'Phát trực tiếp'),
    'description' => sanitize($data['description'] ?? ''),
    'author_id' => $user['id'],
    'author_name' => $user['username'],
    'author_avatar' => $user['avatar'] ?? null,
    'status' => 'live',
    'viewers' => [],
    'viewer_count' => 0,
    'comments' => [],
    'started_at' => date('c'),
    'ended_at' => null,
    'duration' => 0,
    'media_url' => null,
    'thumbnail' => null
];

$livestreams[] = $stream;
writeJson(LIVESTREAMS_FILE, $livestreams);

jsonResponse([
    'success' => true,
    'message' => 'Bắt đầu phát trực tiếp',
    'stream' => $stream
], 201);
