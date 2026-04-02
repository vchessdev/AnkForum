<?php
// ============================================================
// AnkForum - api/uploads/base64.php
// Upload image as base64 data URL (no server storage)
// ============================================================

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../helpers.php';

session_start();
requireLogin();

$data = json_decode(file_get_contents('php://input'), true) ?? $_POST;

if (empty($data['image'])) {
    jsonResponse(['error' => 'Image data không được để trống'], 400);
}

// Validate base64 image
$imageData = $data['image'];
if (!preg_match('/^data:image\/(jpeg|png|gif|webp);base64,/', $imageData)) {
    jsonResponse(['error' => 'Invalid image format. Hỗ trợ: JPEG, PNG, GIF, WebP'], 400);
}

// Get size
$size = strlen(base64_decode(explode(',', $imageData)[1]));
if ($size > MAX_UPLOAD_SIZE) {
    jsonResponse(['error' => 'Ảnh quá lớn (tối đa 500MB)'], 400);
}

// Return the data URL directly - no server storage
jsonResponse([
    'success' => true,
    'url' => $imageData,
    'size' => $size
]);
