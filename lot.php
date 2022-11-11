<?php

/**
 * @var array $categories
 * @var array $lots
 * @var string $title
 * @var integer $is_auth
 * @var string $user_name
 */

require_once('functions/settings.php');

echo '<pre>' . var_export($_GET['id'], true) . '</pre>';
echo '<pre>' . var_export($lots, true) . '</pre>';

if ($_GET['id'] && isset($lot['id'])) {
    $content = include_template('lot.php', [
        'categories' => $categories,
        'lots' => $lots
    ]);
    echo(include_template('layout.php', [
        'title' => $title,
        'is_auth' => $is_auth,
        'user_name' => $user_name,
        'categories' => $categories,
        'content' => $content
    ]));
} else {
    http_response_code(404);
}
