<?php
require_once __DIR__ . '/configs/settings.php';

$title = '404';

$content = include_template('404.php', [
    'categories' => $categories
]);

print(include_template('layout.php', [
    'title' => $title,
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'categories' => $categories,
    'content' => $content
]));
