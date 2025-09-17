<?php
date_default_timezone_set('Europe/Minsk');
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/helpers.php';

$title = 'Главная';
$is_auth = rand(0, 1);
$user_name = 'Иван';
$categories = fetchCategories($con);
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!lotExistsById($con, $id)) {
    http_response_code(404);
    header("Location: /404.php");
}

$lot = fetchLotById($con, $id);

$content = include_template('lot.php', [
    'categories' => $categories,
    'lot' => $lot,
]);

print(include_template('layout.php', [
    'title' => $title,
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'categories' => $categories,
    'content' => $content
]));
