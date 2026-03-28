<?php
// ============================================================
// AnkForum - api/users/update.php
// Update profile: avatar, banner, bio, display_name, password
// ============================================================
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../helpers.php';
session_start();
requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['error' => 'Method not allowed'], 405);
}

$user    = currentUser();
$updates = [];

// ── Avatar upload ─────────────────────────────────────────────
if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] !== UPLOAD_ERR_NO_FILE) {
    $file = $_FILES['avatar'];

    if ($file['error'] !== UPLOAD_ERR_OK) {
        jsonResponse(['error' => 'Lỗi upload avatar: ' . uploadErrorMessage($file['error'])], 422);
    }

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime  = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mime, ALLOWED_IMAGE_TYPES)) {
        jsonResponse(['error' => 'Avatar chỉ được là ảnh (JPG, PNG, GIF, WebP)'], 422);
    }

    if ($file['size'] > 10 * 1024 * 1024) {
        jsonResponse(['error' => 'Ảnh đại diện tối đa 10MB'], 422);
    }

    $uploadDir = UPLOADS_PATH . '/avatars';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
    if (!is_writable($uploadDir)) {
        jsonResponse(['error' => 'Thư mục uploads/avatars thiếu quyền ghi (chmod 777)'], 500);
    }

    $ext      = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $safeExt  = in_array($ext, ['jpg','jpeg','png','gif','webp']) ? $ext : 'jpg';
    $filename = generateId() . '.' . $safeExt;
    $dest     = $uploadDir . '/' . $filename;

    if (!move_uploaded_file($file['tmp_name'], $dest)) {
        jsonResponse(['error' => 'Không thể lưu avatar — kiểm tra chmod uploads/avatars/'], 500);
    }

    if (!empty($user['avatar']) && $user['avatar'] !== 'default.png') {
        $oldPath = ROOT_PATH . '/' . $user['avatar'];
        if (file_exists($oldPath)) @unlink($oldPath);
    }

    $updates['avatar'] = 'uploads/avatars/' . $filename;
}

// ── Banner upload ─────────────────────────────────────────────
if (isset($_FILES['banner']) && $_FILES['banner']['error'] !== UPLOAD_ERR_NO_FILE) {
    $file = $_FILES['banner'];

    if ($file['error'] !== UPLOAD_ERR_OK) {
        jsonResponse(['error' => 'Lỗi upload banner: ' . uploadErrorMessage($file['error'])], 422);
    }

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime  = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mime, ALLOWED_IMAGE_TYPES)) {
        jsonResponse(['error' => 'Banner chỉ được là ảnh (JPG, PNG, GIF, WebP)'], 422);
    }

    if ($file['size'] > 20 * 1024 * 1024) {
        jsonResponse(['error' => 'Ảnh bìa tối đa 20MB'], 422);
    }

    $uploadDir = UPLOADS_PATH . '/banners';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
    if (!is_writable($uploadDir)) {
        jsonResponse(['error' => 'Thư mục uploads/banners thiếu quyền ghi (chmod 777)'], 500);
    }

    $ext      = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $safeExt  = in_array($ext, ['jpg','jpeg','png','gif','webp']) ? $ext : 'jpg';
    $filename = generateId() . '.' . $safeExt;
    $dest     = $uploadDir . '/' . $filename;

    if (!move_uploaded_file($file['tmp_name'], $dest)) {
        jsonResponse(['error' => 'Không thể lưu banner — kiểm tra chmod uploads/banners/'], 500);
    }

    if (!empty($user['banner']) && $user['banner'] !== 'default.jpg') {
        $oldPath = ROOT_PATH . '/' . $user['banner'];
        if (file_exists($oldPath)) @unlink($oldPath);
    }

    $updates['banner'] = 'uploads/banners/' . $filename;
}

// ── Bio ───────────────────────────────────────────────────────
$bio = $_POST['bio'] ?? null;
if ($bio !== null) {
    $bio = trim($bio);
    if (mb_strlen($bio) > 300) jsonResponse(['error' => 'Bio tối đa 300 ký tự'], 422);
    $updates['bio'] = sanitize($bio);
}

// ── Display name ──────────────────────────────────────────────
$displayName = $_POST['display_name'] ?? null;
if ($displayName !== null) {
    $displayName = trim($displayName);
    if (mb_strlen($displayName) > 50) jsonResponse(['error' => 'Tên hiển thị tối đa 50 ký tự'], 422);
    $updates['display_name'] = sanitize($displayName);
}

// ── Change password ───────────────────────────────────────────
$newPass = $_POST['new_password'] ?? '';
$curPass = $_POST['current_password'] ?? '';
if (!empty($newPass)) {
    if (!password_verify($curPass, $user['password'])) {
        jsonResponse(['error' => 'Mật khẩu hiện tại không đúng'], 422);
    }
    if (strlen($newPass) < 6) jsonResponse(['error' => 'Mật khẩu mới ít nhất 6 ký tự'], 422);
    if (!preg_match('/[A-Za-z]/', $newPass) || !preg_match('/[0-9]/', $newPass)) {
        jsonResponse(['error' => 'Mật khẩu phải có chữ và số'], 422);
    }
    $updates['password'] = password_hash($newPass, PASSWORD_BCRYPT, ['cost' => 12]);
}

if (empty($updates)) {
    jsonResponse(['error' => 'Không có gì để cập nhật'], 422);
}

if (!updateUser($user['id'], $updates)) {
    jsonResponse(['error' => 'Không thể lưu — kiểm tra quyền ghi data/users.json'], 500);
}

// Re-read fresh
$users = readJson(USERS_FILE);
$updatedUser = $user;
foreach ($users as $u) {
    if ($u['id'] === $user['id']) { $updatedUser = $u; break; }
}

jsonResponse([
    'success' => true,
    'message' => 'Cập nhật thành công!',
    'user'    => [
        'id'           => $updatedUser['id'],
        'username'     => $updatedUser['username'],
        'display_name' => $updatedUser['display_name'] ?? $updatedUser['username'],
        'bio'          => $updatedUser['bio'] ?? '',
        'avatar'       => avatarUrl($updatedUser['avatar']),
        'banner'       => bannerUrl($updatedUser['banner']),
        'level'        => $updatedUser['level'] ?? 1,
        'points'       => $updatedUser['points'] ?? 0,
    ],
]);
