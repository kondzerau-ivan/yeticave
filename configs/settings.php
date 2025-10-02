<?php
session_start();
date_default_timezone_set('Europe/Minsk');
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/../functions.php';
require_once __DIR__ . '/../helpers.php';

$categories = fetchCategories($con);
$categories_id = array_column($categories, 'id');

if (!isset($_SESSION['user'])) {
    $is_auth = false;
    $user_name = '';
} else {
    $is_auth = true;
    $user_name = $_SESSION['user']['name'];
}
