<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$connection = new mysqli('mysql', 'root', 'root', 'yeticave');

if (!$connection) {
  throw new RuntimeException('Ошибка соединения pdo: ');
} else {
  $connection->set_charset('utf8');
}



// $categories = [
//   'boards' => 'Доски и лыжи',
//   'attachment' => 'Крепления',
//   'boots' => 'Ботинки',
//   'clothing' => 'Одежда',
//   'tools' => 'Инструменты',
//   'other' => 'Разное'
// ];

// $lots = [
//   [
//     'title' => '2014 Rossignol District Snowboard',
//     'category' => $categories['boards'],
//     'price' => 10999,
//     'image' => 'img/lot-1.jpg',
//     'date_end' => '2022-10-15'
//   ],
//   [
//     'title' => 'DC Ply Mens 2016/2017 Snowboard',
//     'category' => $categories['boards'],
//     'price' => 159999,
//     'image' => 'img/lot-2.jpg',
//     'date_end' => '2022-10-15'
//   ],
//   [
//     'title' => 'Крепления Union Contact Pro 2015 года размер L/XL',
//     'category' => $categories['attachment'],
//     'price' => 8000,
//     'image' => 'img/lot-3.jpg',
//     'date_end' => '2022-10-12'
//   ],
//   [
//     'title' => 'Ботинки для сноуборда DC Mutiny Charocal',
//     'category' => $categories['boots'],
//     'price' => 10999,
//     'image' => 'img/lot-4.jpg',
//     'date_end' => '2022-10-12 04:20'
//   ],
//   [
//     'title' => 'Куртка для сноуборда DC Mutiny Charocal',
//     'category' => $categories['clothing'],
//     'price' => 7500,
//     'image' => 'img/lot-5.jpg',
//     'date_end' => '2022-10-13'
//   ],
//   [
//     'title' => 'Маска Oakley Canopy',
//     'category' => $categories['other'],
//     'price' => 5400,
//     'image' => 'img/lot-6.jpg',
//     'date_end' => '2022-10-14'
//   ]
// ];