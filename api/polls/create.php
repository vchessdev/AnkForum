<?php
// ============================================================
// AnkForum - api/polls/create.php
// Create a new poll
// ============================================================

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../helpers.php';

session_start();
requireLogin();

$user = currentUser();
$data = json_decode(file_get_contents('php://input'), true) ?? $_POST;

// Validate input
if (empty($data['title']) || empty($data['options'])) {
    jsonResponse(['error' => 'Tiêu đề và các lựa chọn không được để trống'], 400);
}

if (!is_array($data['options']) || count($data['options']) < 2) {
    jsonResponse(['error' => 'Poll phải có ít nhất 2 lựa chọn'], 400);
}

if (!verifyCsrf($data['csrf_token'] ?? '')) {
    jsonResponse(['error' => 'CSRF token invalid'], 403);
}

// Create poll
$poll = [
    'id' => generateId('poll_'),
    'title' => sanitize($data['title']),
    'description' => sanitize($data['description'] ?? ''),
    'options' => array_map(function($opt) {
        return [
            'id' => generateId('opt_'),
            'text' => sanitize($opt),
            'votes' => 0
        ];
    }, $data['options']),
    'post_id' => $data['post_id'] ?? null,
    'author_id' => $user['id'],
    'author_name' => $user['username'],
    'author_avatar' => $user['avatar'] ?? null,
    'voters' => [],
    'multiple_choice' => $data['multiple_choice'] ?? false,
    'created_at' => date('c'),
    'updated_at' => date('c'),
    'expires_at' => $data['expires_at'] ?? null
];

$polls = readJson(POLLS_FILE);
$polls[] = $poll;
writeJson(POLLS_FILE, $polls);

jsonResponse([
    'success' => true,
    'message' => 'Poll được tạo thành công',
    'poll' => $poll
], 201);
