<?php
date_default_timezone_set('Europe/Minsk');

require_once('functions/db.php');
require_once('functions/functions.php');
require_once ('functions/fetchers.php');
require_once('helpers.php');

$is_auth = rand(0, 1);
$title = 'Мой заголовок';
$user_name = 'Иван';
$categories = fetchCategories();
$lots = fetchLots();