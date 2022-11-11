<?php
require_once('db.php');

/**
 * Use to get Categories
 * @return array - symbol and title
 */
function fetchCategories(): array
{
    global $connection;
    $sql = "SELECT symbol, title
            FROM categories";
    $query = mysqli_query($connection, $sql);

    return mysqli_fetch_all($query, MYSQLI_ASSOC);
}

/**
 * Use to get Lots
 * @return array - array of Lots
 */
function fetchLots(): array
{
    global $connection;
    $sql = "SELECT lots.id, lots.title, lots.start_price AS price, lots.image_url AS image, lots.finished_at, categories.title AS category
            FROM lots INNER JOIN categories ON lots.category_id = categories.id
            WHERE lots.finished_at > NOW()
            ORDER BY lots.finished_at DESC
            LIMIT 3";
    $query = mysqli_query($connection, $sql);

    return mysqli_fetch_all($query, MYSQLI_ASSOC);
}
