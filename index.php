<?php
require_once __DIR__ . '/configs/settings.php';

$title = 'Главная';
$lots = fetchLots($con);

$content = include_template('main.php', [
    'categories' => $categories,
    'lots' => $lots,
]);

print(include_template('layout.php', [
    'title' => $title,
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'categories' => $categories,
    'content' => $content
]));
