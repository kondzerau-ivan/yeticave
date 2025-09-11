<?php
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/helpers.php';

$title = 'Главная';
$is_auth = rand(0, 1);
$user_name = 'Иван';
$categories = [
    'boards' => 'Доски и лыжи',
    'attachment' => 'Крепления',
    'boots' => 'Ботинки',
    'clothing' => 'Одежда',
    'tools' => 'Инструменты',
    'other' => 'Разное',
];
$lots = [
    [
        'name' => '2014 Rossignol District Snowboard',
        'category' => $categories['boards'],
        'price' => '10999',
        'image' => 'img/lot-1.jpg'
    ],
    [
        'name' => 'DC Ply Mens 2016/2017 Snowboard',
        'category' => $categories['boards'],
        'price' => '159999',
        'image' => 'img/lot-2.jpg'
    ],
    [
        'name' => 'Крепления Union Contact Pro 2015 года размер L/XL',
        'category' => $categories['attachment'],
        'price' => '8000',
        'image' => 'img/lot-3.jpg'
    ],
    [
        'name' => 'Ботинки для сноуборда DC Mutiny Charocal',
        'category' => $categories['boots'],
        'price' => '10999',
        'image' => 'img/lot-4.jpg'
    ],
    [
        'name' => 'Куртка для сноуборда DC Mutiny Charocal',
        'category' => $categories['clothing'],
        'price' => '7500',
        'image' => 'img/lot-5.jpg'
    ],
    [
        'name' => 'Маска Oakley Canopy',
        'category' => $categories['other'],
        'price' => '5400',
        'image' => 'img/lot-6.jpg'
    ]
];
$content = include_template('main.php', [
    'categories' => $categories,
    'lots' => $lots,
]);

print(include_template('layout.php', [
    'title' => $title,
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'categories' => $categories,
    'content' => $content
]));
