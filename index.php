<?php

/**
 * @var array $categories
 * @var array $lots
 * @var string $title
 * @var integer $is_auth
 * @var string $user_name
 */

require_once('functions/settings.php');

$lots = fetchLots();

$content = include_template('main.php', [
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
