<?php
require_once __DIR__ . '/configs/settings.php';

$title = 'Лот';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!lotExistsById($con, $id)) {
    http_response_code(404);
    header("Location: /404.php");
    exit;
}

$lot = fetchLotById($con, $id);

$content = include_template('lot.php', [
    'navigation' => $navigation,
    'is_auth' => $is_auth,
    'lot' => $lot,
]);

print(include_template('layout.php', [
    'title' => $title,
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'navigation' => $navigation,
    'content' => $content
]));
