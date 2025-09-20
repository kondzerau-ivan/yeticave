<?php
require_once __DIR__ . '/configs/settings.php';

$title = 'Добавить лот';
$categories = fetchCategories($con);

$content = include_template('add.php', [
    'categories' => $categories
]);

print(include_template('layout.php', [
    'title' => $title,
    'flatpickr' => true,
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'categories' => $categories,
    'content' => $content
]));
