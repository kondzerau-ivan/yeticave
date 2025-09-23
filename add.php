<?php
require_once __DIR__ . '/configs/settings.php';
require_once __DIR__ . '/validate.php';

$title = 'Добавить лот';
$categories = fetchCategories($con);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];

    foreach ($rules as $key => $rule) {
        if (isset($_POST[$key]) || isset($_FILES[$key])) {
            $errors[$key] = array_filter($rule($key));
        }
    }

    if (empty($errors)) {
        header("Location: /lot.php");
    } else {
        $content = include_template('add.php', [
            'categories' => $categories,
            'getPostVal' => fn($name) => getPostVal($name),
            'errors' => array_filter($errors)
        ]);
    }
} else {
    $content = include_template('add.php', [
        'categories' => $categories,
    ]);
}

print(include_template('layout.php', [
    'title' => $title,
    'flatpickr' => true,
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'categories' => $categories,
    'content' => $content
]));
