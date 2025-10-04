<?php
require_once __DIR__ . '/configs/settings.php';
require_once __DIR__ . '/functions.php';

$title = '';

$id = (int) $_GET['category_id'] ?? 0;

if ($id && in_array($id, $categories_id)) {
    $currentPage = $_GET['page'] ?? 1;
    $pageItems = 9;
    $offset = ($currentPage - 1) * $pageItems;
    $lotsCount = getCountLotsByCategory($con, $id);

    $pagesCount = (int) ceil($lotsCount / $pageItems);
    $pages = $pagesCount ? range(1, $pagesCount) : [];

    $lots = findLotsByCategory($con, $id, $pageItems, $offset);
    $selectedCategory = fetchCategoryById($con, $id);

    $content = include_template('all-lots.php', [
        'navigation' => $navigation,
        'selectedCategory' => $selectedCategory,
        'lots' => $lots,
        'pagesCount' => $pagesCount,
        'pages' => $pages,
        'currentPage' => $currentPage
    ]);
} else {
    header("Location:/404.php");
}

print(include_template('layout.php', [
    'title' => $title,
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'navigation' => $navigation,
    'content' => $content
]));
