<?php
// ============================================================
// AnkForum - router.php
// Simple front-controller / router
// ============================================================

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/helpers.php';

session_start();

// Regenerate session periodically (security)
if (empty($_SESSION['last_regen']) || time() - $_SESSION['last_regen'] > 1800) {
    session_regenerate_id(true);
    $_SESSION['last_regen'] = time();
}

// ── API Routes ────────────────────────────────────────────────

$requestUri  = $_SERVER['REQUEST_URI'] ?? '/';
$path        = parse_url($requestUri, PHP_URL_PATH);
$path        = rtrim($path, '/') ?: '/';

// Strip base path if needed
$basePath = '';
$path = substr($path, strlen($basePath)) ?: '/';

// API routing
if (strpos($path, '/api/') === 0) {
    $apiFile = API_PATH . str_replace('/api', '', $path) . '.php';
    if (file_exists($apiFile)) {
        require $apiFile;
    } else {
        jsonResponse(['error' => 'API endpoint không tồn tại'], 404);
    }
    exit;
}

// ── Page Routes ───────────────────────────────────────────────

$page = $_GET['page'] ?? 'home';
$validPages = [
    'home', 'login', 'register', 'logout',
    'profile', 'post', 'search', 'notifications',
    'settings',
];

if (!in_array($page, $validPages)) {
    $page = 'home';
}

// Redirect logged-in users away from auth pages
if (in_array($page, ['login', 'register']) && isLoggedIn()) {
    redirect('/');
}

// These pages require login
$protectedPages = ['notifications', 'settings'];
if (in_array($page, $protectedPages)) {
    requireLogin();
}

return $page;
