<?php
// ============================================================
// AnkForum - api/auth/login.php
// ============================================================
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../helpers.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['error' => 'Method not allowed'], 405);
}

$input = json_decode(file_get_contents('php://input'), true);
if (!$input) $input = $_POST;

$login    = trim($input['login'] ?? '');
$password = $input['password'] ?? '';

if (empty($login) || empty($password)) {
    jsonResponse(['error' => 'Vui lòng nhập đầy đủ thông tin'], 422);
}

// Find user by username or email
$users = readJson(USERS_FILE);
$found = null;
foreach ($users as $u) {
    if (strtolower($u['username']) === strtolower($login)
        || strtolower($u['email']) === strtolower($login)) {
        $found = $u;
        break;
    }
}

if (!$found || !password_verify($password, $found['password'])) {
    jsonResponse(['error' => 'Tên đăng nhập hoặc mật khẩu không đúng'], 401);
}

// Update last seen & online status
updateUser($found['id'], ['is_online' => true, 'last_seen' => date('Y-m-d H:i:s')]);

$_SESSION['user_id'] = $found['id'];
$_SESSION['last_regen'] = time();
session_regenerate_id(true);

jsonResponse([
    'success' => true,
    'message' => 'Đăng nhập thành công! Chào mừng trở lại, ' . $found['username'],
    'user'    => publicUser($found),
]);
