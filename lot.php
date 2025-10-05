<?php
require_once __DIR__ . '/configs/settings.php';
require_once __DIR__ . '/validate.php';

$title = 'Лот';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!lotExistsById($con, $id)) {
    http_response_code(404);
    header("Location: /404.php");
    exit;
}

$lot = fetchLotById($con, $id);
$userId = $_SESSION['user']['id'];
$isBetAllowed = true;

if ($is_auth && $lot['author_id'] === $userId) {
    $isBetAllowed = false;
}

$betsHistory = fetchBetsHistory($con, $lot['id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $step = $lot['step'];
    $price = $lot['start_price'];
    $userBet = $_POST['cost'] ?? 0;

    $rule = fn($value) => [
        validateFilled($value),
        validateBet($price, $step, $value),
    ];

    $errors = [];

    $fieldErrors = array_filter($rule($userBet));

    if (!empty($fieldErrors)) {
        $errors['cost'] = $fieldErrors;
    }

    if (empty($errors)) {
        addNewBet($con, $userBet, $userId, $lot['id']);

        $lot = fetchLotById($con, $id);

        $content = include_template('lot.php', [
            'navigation' => $navigation,
            'is_auth' => $is_auth,
            'isBetAllowed' => $isBetAllowed,
            'lot' => $lot,
            'betsHistory' => $betsHistory,
            'errors' => array_filter($errors)
        ]);
    } else {
        $content = include_template('lot.php', [
            'navigation' => $navigation,
            'is_auth' => $is_auth,
            'isBetAllowed' => $isBetAllowed,
            'lot' => $lot,
            'betsHistory' => $betsHistory,
            'errors' => array_filter($errors)
        ]);
    }
} else {
    $content = include_template('lot.php', [
        'navigation' => $navigation,
        'is_auth' => $is_auth,
        'isBetAllowed' => $isBetAllowed,
        'lot' => $lot,
        'betsHistory' => $betsHistory
    ]);
}

print(include_template('layout.php', [
    'title' => $title,
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'navigation' => $navigation,
    'content' => $content
]));
