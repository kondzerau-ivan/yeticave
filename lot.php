<?php
require_once __DIR__ . '/configs/settings.php';

$title = 'Лот';
$categories = fetchCategories($con);
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!lotExistsById($con, $id)) {
    http_response_code(404);
    header("Location: /404.php");
    exit;
}

$lot = fetchLotById($con, $id);

$content = include_template('lot.php', [
    'categories' => $categories,
    'is_auth' => $is_auth,
    'lot' => $lot,
]);

print(include_template('layout.php', [
    'title' => $title,
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'categories' => $categories,
    'content' => $content
]));
