<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../helpers.php';
session_start();
if (isLoggedIn()) {
    updateUser($_SESSION['user_id'], ['is_online' => false, 'last_seen' => date('Y-m-d H:i:s')]);
}
session_destroy();
if (isAjax()) {
    jsonResponse(['success' => true]);
} else {
    redirect('/?page=login');
}
