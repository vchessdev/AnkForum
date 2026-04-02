<?php
// ============================================================
// AnkForum - api/livestreams/list.php
// Get livestreams
// ============================================================

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../helpers.php';

session_start();

$status = $_GET['status'] ?? 'live'; // live, ended, all
$authorId = $_GET['author_id'] ?? null;

$livestreams = readJson(LIVESTREAMS_FILE);
$result = [];

foreach ($livestreams as $stream) {
    if ($authorId && $stream['author_id'] !== $authorId) {
        continue;
    }
    
    if ($status !== 'all' && $stream['status'] !== $status) {
        continue;
    }
    
    $result[] = $stream;
}

// Sort by started_at descending
usort($result, function($a, $b) {
    return strtotime($b['started_at']) - strtotime($a['started_at']);
});

jsonResponse(['streams' => $result]);
