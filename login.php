<?php
require_once __DIR__ . '/configs/settings.php';
require_once __DIR__ . '/validate.php';

$title = 'Вход';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $rules = [
        'email' => fn($value) => [
            validateFilled($value),
            validateEmail($value)
        ],
        'password' => fn($value) => [
            validateFilled($value),
        ]
    ];

    $errors = [];

    $loginData = getLoginPostData();

    foreach ($loginData as $field => $value) {
        if (isset($rules[$field])) {
            $rule = $rules[$field];
            $fieldErrors = array_filter($rule($value));
            if (!empty($fieldErrors)) {
                $errors[$field] = $fieldErrors;
            }
        }
    }

    $user = getUserByEmail($con, $loginData['email']);

    if (!$user) {
        $errors['email'] = 'Несуществующий email.';
    }

    if ($user && empty($errors) && !password_verify($loginData['password'], $user['password'])) {
        $errors['password'] = 'Неверный пароль.';
    }

    if (empty($errors)) {
        $_SESSION['user'] = $user;
        header('Location: /index.php');
		exit;
    } else {
        $content = include_template('login.php', [
            'navigation' => $navigation,
            'errors' => $errors,
            'loginData' => $loginData
        ]);
    }
} else {
    if (isset($_SESSION['user'])) {
        header('Location: /index.php');
        exit;
    }

    $content = include_template('login.php', [
        'navigation' => $navigation
    ]);
}

print(include_template('layout.php', [
    'title' => $title,
    'is_auth' => $is_auth,
    'navigation' => $navigation,
    'content' => $content
]));
