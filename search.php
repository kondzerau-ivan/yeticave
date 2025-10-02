<?php
require_once __DIR__ . '/configs/settings.php';

$title = 'Результаты поиска';
$searchTarget = getSearchData();

if ($searchTarget) {
    $currentPage = $_GET['page'] ?? 1;
    $pageItems = 9;
    $offset = ($currentPage - 1) * $pageItems;
    $lotsCount = getCountLots($con, $searchTarget);

    $pagesCount = (int) ceil($lotsCount / $pageItems);
    $pages = $pagesCount ? range(1, $pagesCount, 1) : [];


    $lots = findLots($con, $searchTarget, $pageItems, $offset);

    $content = include_template('search.php', [
        'categories' => $categories,
        'searchTarget' => $searchTarget,
        'lots' => $lots,
        'pagesCount' => $pagesCount,
        'pages' => $pages,
        'currentPage' => $currentPage
    ]);
} else {
    $content = include_template('search.php', [
        'categories' => $categories,
        'searchTarget' => $searchTarget,
    ]);
}

print(include_template('layout.php', [
    'title' => $title,
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'categories' => $categories,
    'searchTarget' => $searchTarget,
    'content' => $content
]));
