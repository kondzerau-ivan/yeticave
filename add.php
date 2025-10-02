<?php
require_once __DIR__ . '/configs/settings.php';
require_once __DIR__ . '/validate.php';

$title = 'Добавить лот';

if (!$is_auth) {
    http_response_code(403);
    exit;
}

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
        'image' => fn($value) => [
            validateFile($value)
        ],
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
            $fieldErrors = array_filter($rule($value));
            if (!empty($fieldErrors)) {
                $errors[$field] = $fieldErrors;
            }
        }
    }

    if (empty($errors)) {
        $id = addNewLot($con, $lot);
        header("Location: /lot.php?id={$id}");
        exit;
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
