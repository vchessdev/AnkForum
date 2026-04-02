<?php
// ============================================================
// AnkForum - api/livestreams/get.php
// Get single livestream detail
// ============================================================

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../helpers.php';

session_start();

$streamId = $_GET['id'] ?? null;

if (!$streamId) {
    jsonResponse(['error' => 'Stream ID không được để trống'], 400);
}

$livestreams = readJson(LIVESTREAMS_FILE);

foreach ($livestreams as $stream) {
    if ($stream['id'] === $streamId) {
        jsonResponse(['stream' => $stream]);
    }
}

jsonResponse(['error' => 'Stream không tồn tại'], 404);
