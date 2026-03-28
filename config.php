<?php
// ============================================================
// AnkForum - config.php
// Global configuration
// ============================================================

define('APP_NAME', 'AnkForum');
define('APP_URL', 'https://ankb.work.gd');
define('APP_VERSION', '1.0.0');

// Paths
define('ROOT_PATH', __DIR__);
define('DATA_PATH', ROOT_PATH . '/data');
define('UPLOADS_PATH', ROOT_PATH . '/uploads');
define('COMPONENTS_PATH', ROOT_PATH . '/components');
define('PAGES_PATH', ROOT_PATH . '/pages');
define('API_PATH', ROOT_PATH . '/api');

// Data files
define('USERS_FILE', DATA_PATH . '/users.json');
define('POSTS_FILE', DATA_PATH . '/posts.json');
define('COMMENTS_FILE', DATA_PATH . '/comments.json');
define('NOTIFICATIONS_FILE', DATA_PATH . '/notifications.json');

// Upload settings
define('MAX_UPLOAD_SIZE', 500 * 1024 * 1024); // 500MB
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/gif', 'image/webp']);
define('ALLOWED_VIDEO_TYPES', ['video/mp4', 'video/webm', 'video/ogg', 'video/quicktime']);
define('ALLOWED_FILE_TYPES', array_merge(
    ALLOWED_IMAGE_TYPES,
    ALLOWED_VIDEO_TYPES,
    ['application/pdf']
));

// Pagination
define('POSTS_PER_PAGE', 10);
define('COMMENTS_PER_PAGE', 20);
define('NOTIFICATIONS_PER_PAGE', 20);

// Session
define('SESSION_LIFETIME', 86400 * 7); // 7 days

// Level thresholds (based on activity points)
define('LEVEL_THRESHOLDS', [
    1 => 0,
    2 => 50,
    3 => 150,
    4 => 350,
    5 => 700,
    6 => 1200,
    7 => 2000,
    8 => 3500,
    9 => 6000,
    10 => 10000,
]);

// Level badges
define('LEVEL_BADGES', [
    1  => ['name' => 'Newbie',     'color' => '#94a3b8', 'icon' => '🌱'],
    2  => ['name' => 'Member',     'color' => '#22d3ee', 'icon' => '⭐'],
    3  => ['name' => 'Active',     'color' => '#34d399', 'icon' => '🔥'],
    4  => ['name' => 'Regular',    'color' => '#818cf8', 'icon' => '💎'],
    5  => ['name' => 'Veteran',    'color' => '#f59e0b', 'icon' => '🏆'],
    6  => ['name' => 'Expert',     'color' => '#f97316', 'icon' => '🚀'],
    7  => ['name' => 'Elite',      'color' => '#ec4899', 'icon' => '👑'],
    8  => ['name' => 'Legend',     'color' => '#a855f7', 'icon' => '⚡'],
    9  => ['name' => 'Master',     'color' => '#ef4444', 'icon' => '🌟'],
    10 => ['name' => 'God',        'color' => '#eab308', 'icon' => '🔱'],
]);

// Error reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', ROOT_PATH . '/error.log');

// Timezone
date_default_timezone_set('Asia/Ho_Chi_Minh');

// Session config
ini_set('session.cookie_lifetime', SESSION_LIFETIME);
ini_set('session.gc_maxlifetime', SESSION_LIFETIME);
