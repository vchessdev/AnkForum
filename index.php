<?php
// ============================================================
// AnkForum - index.php
// Main entry point
// ============================================================

$page = require_once __DIR__ . '/router.php';

// Handle logout inline
if ($page === 'logout') {
    session_destroy();
    redirect('/?page=login');
}

$user = currentUser();
$csrfToken = csrfToken();
?>
<!DOCTYPE html>
<html lang="vi" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="AnkForum - Cộng đồng chia sẻ nội dung hàng đầu Việt Nam">
    <meta name="theme-color" content="#0f0f1a">
    <title>AnkForum<?php echo $page !== 'home' ? ' — ' . ucfirst($page) : ''; ?></title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700;800;900&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- TailwindCSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Be Vietnam Pro', 'sans-serif'],
                        display: ['Space Grotesk', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50:  '#f0f4ff',
                            100: '#e0eaff',
                            200: '#c7d7fe',
                            300: '#a5b8fc',
                            400: '#818cf8',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            800: '#3730a3',
                            900: '#312e81',
                        },
                        surface: {
                            50:  '#f8fafc',
                            100: '#f1f5f9',
                            200: '#e2e8f0',
                            800: '#1e1e2e',
                            850: '#181825',
                            900: '#11111b',
                            950: '#0a0a14',
                        },
                    },
                    animation: {
                        'fade-in':    'fadeIn 0.4s ease-out',
                        'slide-up':   'slideUp 0.4s ease-out',
                        'slide-down': 'slideDown 0.3s ease-out',
                        'scale-in':   'scaleIn 0.3s ease-out',
                        'pulse-slow': 'pulse 3s infinite',
                        'glow':       'glow 2s ease-in-out infinite alternate',
                    },
                    keyframes: {
                        fadeIn:    { from: { opacity: '0' }, to: { opacity: '1' } },
                        slideUp:   { from: { opacity: '0', transform: 'translateY(16px)' }, to: { opacity: '1', transform: 'translateY(0)' } },
                        slideDown: { from: { opacity: '0', transform: 'translateY(-12px)' }, to: { opacity: '1', transform: 'translateY(0)' } },
                        scaleIn:   { from: { opacity: '0', transform: 'scale(0.95)' }, to: { opacity: '1', transform: 'scale(1)' } },
                        glow:      { from: { boxShadow: '0 0 20px #6366f140' }, to: { boxShadow: '0 0 40px #6366f180' } },
                    },
                    backdropBlur: { xs: '2px' },
                },
            },
        }
    </script>

    <!-- Global styles -->
    <link rel="stylesheet" href="assets/css/app.css">

    <!-- CSRF token meta -->
    <meta name="csrf-token" content="<?php echo $csrfToken; ?>">

    <!-- Current user data (if logged in) -->
    <?php if ($user): ?>
    <script>
        window.AnkForum = {
            user: <?php echo json_encode(publicUser($user), JSON_UNESCAPED_UNICODE); ?>,
            csrf: '<?php echo $csrfToken; ?>',
            baseUrl: '<?php echo APP_URL; ?>'
        };
    </script>
    <?php else: ?>
    <script>
        window.AnkForum = { user: null, csrf: '<?php echo $csrfToken; ?>', baseUrl: '<?php echo APP_URL; ?>' };
    </script>
    <?php endif; ?>
</head>
<body class="bg-surface-950 text-slate-100 font-sans antialiased min-h-screen">

    <!-- Animated background -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden z-0" aria-hidden="true">
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-brand-600/10 rounded-full blur-3xl animate-pulse-slow"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-purple-600/8 rounded-full blur-3xl animate-pulse-slow" style="animation-delay:2s"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-indigo-900/5 rounded-full blur-3xl"></div>
    </div>

    <div class="relative z-10">
        <?php if ($page === 'login' || $page === 'register'): ?>
            <!-- Auth pages — no navbar/sidebar -->
            <?php require PAGES_PATH . '/' . $page . '.php'; ?>

        <?php else: ?>
            <!-- App shell with navbar + sidebars -->
            <?php require COMPONENTS_PATH . '/navbar.php'; ?>

            <div class="flex pt-16 min-h-screen">
                <!-- Left Sidebar -->
                <aside class="hidden lg:flex flex-col fixed left-0 top-16 h-[calc(100vh-4rem)] w-64 xl:w-72 z-20">
                    <?php require COMPONENTS_PATH . '/sidebar-left.php'; ?>
                </aside>

                <!-- Main Content -->
                <main class="flex-1 lg:ml-64 xl:ml-72 lg:mr-64 xl:mr-72 max-w-full">
                    <div class="max-w-2xl mx-auto px-4 py-6">
                        <?php
                        $pageFile = PAGES_PATH . '/' . $page . '.php';
                        if (file_exists($pageFile)) {
                            require $pageFile;
                        } else {
                            require PAGES_PATH . '/home.php';
                        }
                        ?>
                    </div>
                </main>

                <!-- Right Sidebar -->
                <aside class="hidden lg:flex flex-col fixed right-0 top-16 h-[calc(100vh-4rem)] w-64 xl:w-72 z-20">
                    <?php require COMPONENTS_PATH . '/sidebar-right.php'; ?>
                </aside>
            </div>
        <?php endif; ?>
    </div>

    <!-- Toast container -->
    <div id="toast-container" class="fixed bottom-6 right-6 z-50 flex flex-col gap-3 pointer-events-none"></div>

    <!-- Notification sound -->
    <audio id="notif-sound" preload="auto">
        <source src="assets/sounds/notification.mp3" type="audio/mpeg">
    </audio>

     <!-- Core JS -->
    <script src="assets/js/app.js"></script>
    <script src="assets/js/ajax.js"></script>

    <?php if (isLoggedIn()): ?>
    <script src="assets/js/notifications.js"></script>
    <?php endif; ?>

    <!-- Init feed on home page -->
    <?php if ($page === 'home'): ?>
    <script>
        if (typeof loadFeed === 'function') {
            loadFeed();
            if (typeof InfiniteScroll !== 'undefined') {
                InfiniteScroll.observe();
                InfiniteScroll.onLoad(() => loadFeed());
            }
        }
    </script>
    <?php endif; ?>
</body>
</html>
