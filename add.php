<?php
require_once __DIR__ . '/configs/settings.php';
require_once __DIR__ . '/validate.php';

$title = 'Добавить лот';
$categories = fetchCategories($con);
$categories_id = array_column($categories, 'id');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rules = [
        'lot-name' => fn($value) => [
            validateFilled($value),
            validateLenght($value, 10, 100)
        ],
        'category' => fn($value) => [
            validateFilled($value),
            validateCategory($value, $categories_id)
        ],
        'message' => fn($value) => [
            validateFilled($value)
        ],
        // 'image' => fn($key) => [
        //     validateFile($key)
        // ],
        'lot-rate' => fn($value) => [
            validateFilled($value),
            validatePrice($value)
        ],
        'lot-step' => fn($value) => [
            validateFilled($value),
            validatePriceStep($value)
        ],
        'lot-date' => fn($value) => [
            validateFilled($value),
            validateDateFormat($value),
            validateDate($value)
        ]
    ];

    $errors = [];

    $lot = getLotPostData();

    foreach ($lot as $field => $value) {
        if (isset($rules[$field])) {
            $rule = $rules[$field];
            $errors[$field] = array_filter($rule($value));
        }
    }

    if (!empty($_FILES['image']['name'])) {
        $fallowed = ['image/jpeg', 'image/png'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $ftype = finfo_file($finfo, $_FILES['image']['tmp_name']);
        finfo_close($finfo);

        if (!in_array($ftype, $fallowed, true)) {
            $errors['image'] = "Файл должен быть изображением (jpeg/png).";
        }else {
            [$fname, $fext] = explode('.', $_FILES['image']['name']);
            $fname = $fname . '__' . uniqid() . '.' . $fext;
            $fpath = __DIR__ . '/uploads/';
            move_uploaded_file($_FILES['image']['tmp_name'], $fpath . $fname);
        }
    } else {
        $errors['image'] = "Загрузка изображения обязательна.";
    }

    if (empty($errors)) {
        var_dump('ОШИБОК НЕТ');
        // header("Location: /lot.php?id={$id}");
    } else {
        $content = include_template('add.php', [
            'categories' => $categories,
            'lot' => $lot,
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
