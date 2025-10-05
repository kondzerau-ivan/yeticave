<?php
require_once __DIR__ . '/configs/settings.php';
require_once __DIR__ . '/functions.php';

$title = 'Мои ставки';

if (!$is_auth) {
    http_response_code(403);
    exit;
}

$id = $_SESSION['user']['id'];

$bets = fetchAllUserBets($con, $id);

$content = include_template('my-bets.php', [
    'navigation' => $navigation,
    'is_auth' => $is_auth,
    'bets' => $bets
]);

print(include_template('layout.php', [
    'title' => $title,
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'navigation' => $navigation,
    'content' => $content
]));
