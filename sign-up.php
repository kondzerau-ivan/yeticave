<?php
require_once __DIR__ . '/configs/settings.php';
require_once __DIR__ . '/validate.php';

$title = "Регистрация";
$categories = fetchCategories($con);

if ($is_auth) {
    http_response_code(403);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rules = [
        'email' => fn($value) => [
            validateFilled($value),
            validateEmail($value),
            validateUniqueEmail($con, $value)
        ],
        'password' => fn($value) => [
            validateFilled($value),
            validateLenght($value, 8)
        ],
        'name' => fn($value) => [
            validateFilled($value),
            validateUniqueName($con, $value)
        ],
        'message' => fn($value) => [
            validateFilled($value),
        ]
    ];

    $errors = [];

    $user = getUserPostData();

    foreach ($user as $field => $value) {
        if (isset($rules[$field])) {
            $rule = $rules[$field];
            $fieldErrors = array_filter($rule($value));
            if (!empty($fieldErrors)) {
                $errors[$field] = $fieldErrors;
            }
        }
    }

    if (empty($errors)) {
        addNewUser($con, $user);
        header("Location: /login.php");
        exit();
    } else {
        $content = include_template('sign-up.php', [
            'categories' => $categories,
            'user' => $user,
            'errors' => array_filter($errors)
        ]);
    }
} else {
    $content = include_template('sign-up.php', [
        'categories' => $categories,
    ]);
}

print(include_template('layout.php', [
    'title' => $title,
    'is_auth' => $is_auth,
    'categories' => $categories,
    'content' => $content
]));
