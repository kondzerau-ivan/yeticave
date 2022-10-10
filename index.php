<?php
require_once('functions/settings.php');

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
