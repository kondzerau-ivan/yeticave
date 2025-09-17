<?php
date_default_timezone_set('Europe/Minsk');
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/helpers.php';

$title = 'Главная';
$is_auth = rand(0, 1);
$user_name = 'Иван';
$categories = fetchCategories($con);

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
