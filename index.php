<?php
date_default_timezone_set('Europe/Minsk');
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/data.php';

$title = 'Главная';
$is_auth = rand(0, 1);
$user_name = 'Иван';

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
