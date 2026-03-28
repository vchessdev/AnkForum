<?php
// ============================================================
// AnkForum - api/auth/register.php
// ============================================================
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../helpers.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['error' => 'Method not allowed'], 405);
}

$input = json_decode(file_get_contents('php://input'), true);
if (!$input) $input = $_POST;

$username = trim($input['username'] ?? '');
$email    = trim($input['email'] ?? '');
$password = $input['password'] ?? '';
$confirm  = $input['confirm_password'] ?? '';

// Validation
$errors = [];

if (empty($username) || strlen($username) < 3 || strlen($username) > 30) {
    $errors['username'] = 'Tên đăng nhập phải từ 3-30 ký tự';
} elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
    $errors['username'] = 'Chỉ dùng chữ cái, số và dấu _';
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'Email không hợp lệ';
}

if (strlen($password) < 6) {
    $errors['password'] = 'Mật khẩu ít nhất 6 ký tự';
} elseif (!preg_match('/[A-Za-z]/', $password) || !preg_match('/[0-9]/', $password)) {
    $errors['password'] = 'Mật khẩu phải có chữ và số';
}

if ($password !== $confirm) {
    $errors['confirm_password'] = 'Mật khẩu xác nhận không khớp';
}

if (!empty($errors)) {
    jsonResponse(['errors' => $errors], 422);
}

// Check uniqueness
$users = readJson(USERS_FILE);
foreach ($users as $u) {
    if (strtolower($u['username']) === strtolower($username)) {
        jsonResponse(['errors' => ['username' => 'Tên đăng nhập đã tồn tại']], 422);
    }
    if (strtolower($u['email']) === strtolower($email)) {
        jsonResponse(['errors' => ['email' => 'Email đã được sử dụng']], 422);
    }
}

// Create user
$user = [
    'id'         => generateId('u'),
    'username'   => sanitize($username),
    'email'      => strtolower($email),
    'password'   => password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]),
    'avatar'     => 'default.png',
    'banner'     => 'default.jpg',
    'bio'        => '',
    'level'      => 1,
    'points'     => 0,
    'followers'  => [],
    'following'  => [],
    'is_online'  => true,
    'last_seen'  => date('Y-m-d H:i:s'),
    'created_at' => date('Y-m-d H:i:s'),
];

$users[] = $user;
if (!writeJson(USERS_FILE, $users)) {
    jsonResponse(['error' => 'Không thể tạo tài khoản, thử lại sau'], 500);
}

// Auto login
$_SESSION['user_id'] = $user['id'];
$_SESSION['last_regen'] = time();

jsonResponse([
    'success' => true,
    'message' => 'Đăng ký thành công! Chào mừng ' . $user['username'],
    'user'    => publicUser($user),
]);
