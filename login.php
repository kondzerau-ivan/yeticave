<?php
require_once __DIR__ . '/configs/settings.php';
require_once __DIR__ . '/validate.php';

$title = 'Вход';
$categories = fetchCategories($con);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
} else {
    $content = include_template('login.php', [
        'categories' => $categories,
    ]);
}

print(include_template('layout.php', [
    'title' => $title,
    'categories' => $categories,
    'content' => $content
]));
