<?php

/**
 * @var array $categories
 * @var array $lots
 * @var string $title
 * @var integer $is_auth
 * @var string $user_name
 */

require_once('functions/settings.php');

if ($_GET['id'] && is_numeric($_GET['id'])) {
    $lot = fetchLotById($_GET['id']);
    if ($lot) {
        $content = include_template('lot.php', [
            'categories' => $categories,
            'lot' => $lot
        ]);
        echo(include_template('layout.php', [
            'title' => $title,
            'is_auth' => $is_auth,
            'user_name' => $user_name,
            'categories' => $categories,
            'content' => $content
        ]));
    } else {
        $content = include_template('404.php', [
            'categories' => $categories,
        ]);
        echo(include_template('layout.php', [
            'title' => $title,
            'is_auth' => $is_auth,
            'user_name' => $user_name,
            'categories' => $categories,
            'content' => $content
        ]));
    }
} else {
    $content = include_template('404.php', [
        'categories' => $categories,
    ]);
    echo(include_template('layout.php', [
        'title' => $title,
        'is_auth' => $is_auth,
        'user_name' => $user_name,
        'categories' => $categories,
        'content' => $content
    ]));
}
