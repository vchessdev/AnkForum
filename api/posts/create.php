<?php
// api/posts/create.php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../helpers.php';
session_start();
requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') jsonResponse(['error' => 'Method not allowed'], 405);

$user    = currentUser();
$content = trim($_POST['content'] ?? '');
$medias  = [];

if (empty($content) && empty($_FILES['media'])) {
    jsonResponse(['error' => 'Bài viết phải có nội dung hoặc media'], 422);
}

if (mb_strlen($content) > 5000) {
    jsonResponse(['error' => 'Nội dung tối đa 5000 ký tự'], 422);
}

// Handle file uploads
if (!empty($_FILES['media']['name'][0])) {
    $files = $_FILES['media'];
    $count = count($files['name']);
    if ($count > 10) jsonResponse(['error' => 'Tối đa 10 file mỗi bài viết'], 422);

    for ($i = 0; $i < $count; $i++) {
        if ($files['error'][$i] !== UPLOAD_ERR_OK) continue;
        $file = [
            'name'     => $files['name'][$i],
            'type'     => $files['type'][$i],
            'tmp_name' => $files['tmp_name'][$i],
            'error'    => $files['error'][$i],
            'size'     => $files['size'][$i],
        ];
        $result = handleUpload($file, 'posts');
        if (isset($result['error'])) jsonResponse(['error' => $result['error']], 422);
        $medias[] = ['path' => $result['path'], 'mime' => $result['mime']];
    }
}

$post = [
    'id'         => generateId('p'),
    'user_id'    => $user['id'],
    'content'    => sanitize($content),
    'media'      => $medias,
    'likes'      => [],
    'comment_count' => 0,
    'created_at' => date('Y-m-d H:i:s'),
    'updated_at' => date('Y-m-d H:i:s'),
];

$posts = readJson(POSTS_FILE);
array_unshift($posts, $post);
writeJson(POSTS_FILE, $posts);

// Award points
$pts = ($user['points'] ?? 0) + 10;
$newLevel = calculateLevel($pts);
updateUser($user['id'], ['points' => $pts, 'level' => $newLevel]);

// Notify followers
$allUsers = readJson(USERS_FILE);
foreach ($allUsers as $u) {
    if (in_array($user['id'], $u['following'] ?? [])) {
        addNotification($u['id'], 'new_post', [
            'from_user_id'   => $user['id'],
            'from_username'  => $user['username'],
            'from_avatar'    => $user['avatar'],
            'post_id'        => $post['id'],
            'post_preview'   => truncate($content, 50),
        ]);
    }
}

// Build post HTML
ob_start();
require __DIR__ . '/../../components/post-card.php';
$postHtml = ob_get_clean();

jsonResponse([
    'success'  => true,
    'post'     => $post,
    'post_html'=> $postHtml,
    'message'  => 'Đăng bài thành công! +10 điểm',
]);
