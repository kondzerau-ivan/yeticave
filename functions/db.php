<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$connection = new mysqli('mysql', 'root', 'root', 'yeticave');

if (!$connection) {
  throw new RuntimeException('Ошибка соединения pdo: ' . mysqli_connect_error());
} else {
  $connection->set_charset('utf8');
}