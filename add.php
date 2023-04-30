<?php

/**
 * @var array $categories
 * @var array $lot
 * @var string $title
 * @var integer $is_auth
 * @var string $user_name
 */

require_once('functions/settings.php');

// 1) Все поля формы обязательны;

// 2) Начальная цена > 0

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($_FILES['lot-img']['type'])) {
        $file_name = uniqid() . getFileType($_FILES['lot-img']['type']);
        move_uploaded_file($_FILES['lot-img']['tmp_name'], 'uploads/' . $file_name);
    }
};

$content = include_template('add-lot.php', [
    'categories' => $categories,
]);
echo(include_template('layout.php', [
    'title' => $title,
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'categories' => $categories,
    'content' => $content
]));
