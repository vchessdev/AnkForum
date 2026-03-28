<?php
// ============================================================
// AnkForum - helpers.php
// Global utility / helper functions
// ============================================================

// ── JSON Storage ─────────────────────────────────────────────

function readJson(string $file): array {
    if (!file_exists($file)) return [];
    $content = file_get_contents($file);
    if (empty($content)) return [];
    $data = json_decode($content, true);
    return is_array($data) ? $data : [];
}

function writeJson(string $file, array $data): bool {
    $dir = dirname($file);
    if (!is_dir($dir)) mkdir($dir, 0755, true);
    $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    // Atomic write using temp file
    $tmp = $file . '.tmp.' . uniqid();
    if (file_put_contents($tmp, $json, LOCK_EX) === false) return false;
    return rename($tmp, $file);
}

// ── ID Generation ─────────────────────────────────────────────

function generateId(string $prefix = ''): string {
    return $prefix . bin2hex(random_bytes(8));
}

// ── Security ──────────────────────────────────────────────────

function sanitize(string $input): string {
    return htmlspecialchars(trim($input), ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

function sanitizeFilename(string $name): string {
    $name = preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', $name);
    return substr($name, 0, 200);
}

function xssSafe(string $html): string {
    // Allow basic tags for post content
    return strip_tags($html, '<p><br><b><i><u><em><strong><a>');
}

function csrfToken(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCsrf(string $token): bool {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// ── Auth Helpers ──────────────────────────────────────────────

function isLoggedIn(): bool {
    return !empty($_SESSION['user_id']);
}

function currentUser(): ?array {
    if (!isLoggedIn()) return null;
    $users = readJson(USERS_FILE);
    foreach ($users as $u) {
        if ($u['id'] === $_SESSION['user_id']) return $u;
    }
    return null;
}

function requireLogin(): void {
    if (!isLoggedIn()) {
        if (isAjax()) {
            jsonResponse(['error' => 'Vui lòng đăng nhập'], 401);
        }
        redirect('/?page=login');
    }
}

// ── Response Helpers ──────────────────────────────────────────

function jsonResponse(array $data, int $status = 200): void {
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function redirect(string $url): void {
    header('Location: ' . $url);
    exit;
}

function isAjax(): bool {
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH'])
        && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}

// ── Date/Time Helpers ─────────────────────────────────────────

function timeAgo(string $datetime): string {
    $now  = new DateTime();
    $then = new DateTime($datetime);
    $diff = $now->diff($then);

    if ($diff->y > 0)  return $diff->y . ' năm trước';
    if ($diff->m > 0)  return $diff->m . ' tháng trước';
    if ($diff->d > 0)  return $diff->d . ' ngày trước';
    if ($diff->h > 0)  return $diff->h . ' giờ trước';
    if ($diff->i > 0)  return $diff->i . ' phút trước';
    return 'Vừa xong';
}

function formatDate(string $datetime): string {
    return (new DateTime($datetime))->format('d/m/Y H:i');
}

// ── Level System ──────────────────────────────────────────────

function calculateLevel(int $points): int {
    $level = 1;
    foreach (LEVEL_THRESHOLDS as $lvl => $threshold) {
        if ($points >= $threshold) $level = $lvl;
    }
    return $level;
}

function getLevelInfo(int $level): array {
    return LEVEL_BADGES[$level] ?? LEVEL_BADGES[1];
}

function getPointsForNextLevel(int $currentPoints): array {
    $currentLevel = calculateLevel($currentPoints);
    $nextLevel = min($currentLevel + 1, 10);
    $currentThreshold = LEVEL_THRESHOLDS[$currentLevel];
    $nextThreshold = LEVEL_THRESHOLDS[$nextLevel] ?? $currentThreshold;

    if ($currentLevel === 10) {
        return ['current' => $currentPoints, 'needed' => 0, 'percent' => 100];
    }

    $progress = $currentPoints - $currentThreshold;
    $total = $nextThreshold - $currentThreshold;
    $percent = $total > 0 ? min(100, round(($progress / $total) * 100)) : 100;

    return [
        'current'  => $currentPoints,
        'needed'   => max(0, $nextThreshold - $currentPoints),
        'percent'  => $percent,
        'next_lvl' => $nextLevel,
    ];
}

// ── File Upload ───────────────────────────────────────────────

function handleUpload(array $file, string $subDir): array {
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['error' => 'Lỗi upload file: ' . uploadErrorMessage($file['error'])];
    }

    if ($file['size'] > MAX_UPLOAD_SIZE) {
        return ['error' => 'File quá lớn (tối đa 500MB)'];
    }

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime  = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mime, ALLOWED_FILE_TYPES)) {
        return ['error' => 'Loại file không được phép: ' . $mime];
    }

    $ext      = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = generateId() . '.' . strtolower($ext);
    $dir      = UPLOADS_PATH . '/' . $subDir;

    if (!is_dir($dir)) mkdir($dir, 0755, true);

    $dest = $dir . '/' . $filename;
    if (!move_uploaded_file($file['tmp_name'], $dest)) {
        return ['error' => 'Không thể lưu file'];
    }

    return ['path' => 'uploads/' . $subDir . '/' . $filename, 'mime' => $mime];
}

function uploadErrorMessage(int $code): string {
    return match($code) {
        UPLOAD_ERR_INI_SIZE   => 'File vượt quá giới hạn server',
        UPLOAD_ERR_FORM_SIZE  => 'File vượt quá giới hạn form',
        UPLOAD_ERR_PARTIAL    => 'Upload không hoàn chỉnh',
        UPLOAD_ERR_NO_FILE    => 'Không có file được chọn',
        UPLOAD_ERR_NO_TMP_DIR => 'Thiếu thư mục tạm',
        UPLOAD_ERR_CANT_WRITE => 'Không thể ghi file',
        default               => 'Lỗi không xác định'
    };
}

// ── User Helpers ──────────────────────────────────────────────

function getUserById(string $id): ?array {
    $users = readJson(USERS_FILE);
    foreach ($users as $u) {
        if ($u['id'] === $id) return $u;
    }
    return null;
}

function getUserByUsername(string $username): ?array {
    $users = readJson(USERS_FILE);
    foreach ($users as $u) {
        if (strtolower($u['username']) === strtolower($username)) return $u;
    }
    return null;
}

function updateUser(string $id, array $updates): bool {
    $users = readJson(USERS_FILE);
    foreach ($users as &$u) {
        if ($u['id'] === $id) {
            $u = array_merge($u, $updates);
            return writeJson(USERS_FILE, $users);
        }
    }
    return false;
}

function publicUser(array $user): array {
    unset($user['password']);
    return $user;
}

// ── Notification Helper ───────────────────────────────────────

function addNotification(string $toUserId, string $type, array $data): void {
    if ($toUserId === ($data['from_user_id'] ?? '')) return; // no self-notify
    $notifications = readJson(NOTIFICATIONS_FILE);
    $notifications[] = [
        'id'         => generateId('n'),
        'user_id'    => $toUserId,
        'type'       => $type,
        'data'       => $data,
        'read'       => false,
        'created_at' => date('Y-m-d H:i:s'),
    ];
    // Keep only last 500 notifications
    if (count($notifications) > 500) {
        $notifications = array_slice($notifications, -500);
    }
    writeJson(NOTIFICATIONS_FILE, $notifications);
}

// ── String Helpers ────────────────────────────────────────────

function truncate(string $text, int $length = 150): string {
    if (mb_strlen($text) <= $length) return $text;
    return mb_substr($text, 0, $length) . '...';
}

function formatNumber(int $n): string {
    if ($n >= 1000000) return round($n / 1000000, 1) . 'M';
    if ($n >= 1000)    return round($n / 1000, 1) . 'K';
    return (string)$n;
}

function avatarUrl(?string $avatar): string {
    if (empty($avatar) || $avatar === 'default.png') {
        return 'assets/images/default-avatar.svg';
    }
    // Ensure leading slash for uploaded files
    return ltrim($avatar, '/');
}

function bannerUrl(?string $banner): string {
    if (empty($banner) || $banner === 'default.jpg') {
        return 'assets/images/default-banner.svg';
    }
    return ltrim($banner, '/');
}
