<?php
require_once __DIR__ . '/configs/settings.php';
require_once __DIR__ . '/validate.php';

$title = "Регистрация";
$categories = fetchCategories($con);

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
    } else {
        $content = include_template('sign-up.php', [
            'categories' => $categories,
            'registrationData' => $registrationData,
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
    'categories' => $categories,
    'content' => $content
]));
