<?php
// ============================================================
// AnkForum - api/livestreams/toggle-screen-share.php
// Toggle screen sharing for active livestream
// ============================================================

require_once '../../helpers.php';
require_once '../../config.php';

header('Content-Type: application/json');

// Verify CSRF token
if ($_POST['csrf'] !== $_SESSION['csrf_token']) {
    http_response_code(403);
    exit(json_encode(['error' => 'Invalid CSRF token']));
}

$user = currentUser();
if (!$user) {
    http_response_code(401);
    exit(json_encode(['error' => 'Not logged in']));
}

$streamId = $_POST['stream_id'] ?? null;
$enableScreenShare = $_POST['enable_screen_share'] === 'true' || $_POST['enable_screen_share'] === '1';

if (!$streamId) {
    http_response_code(400);
    exit(json_encode(['error' => 'Stream ID required']));
}

// Read livestreams
$livestreams = readJson(LIVESTREAMS_FILE);
$updated = false;

foreach ($livestreams as &$stream) {
    if ($stream['id'] === $streamId && $stream['author_id'] === $user['id']) {
        $stream['screen_sharing_enabled'] = $enableScreenShare;
        $stream['screen_share_started_at'] = $enableScreenShare ? date('c') : null;
        $updated = true;
        break;
    }
}

if (!$updated) {
    http_response_code(404);
    exit(json_encode(['error' => 'Stream not found or not authorized']));
}

// Write back to file
writeJson(LIVESTREAMS_FILE, $livestreams);

exit(json_encode([
    'success' => true,
    'screen_sharing_enabled' => $enableScreenShare,
    'message' => $enableScreenShare ? 'Screen sharing started' : 'Screen sharing stopped'
]));
?>
