<?php
require_once('db.php');

/**
 * Use to get Categories
 * @return array - symbol and title
 */
function fetchCategories(): array
{
    global $connection;
    $sql = "
            SELECT
                symbol,
                title
            FROM
                categories
    ";
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
    $sql = "
            SELECT
                lots.id,
                lots.title,
                lots.start_price AS price,
                lots.image_url AS image,
                lots.finished_at,
                categories.title AS category
            FROM
                lots
            INNER JOIN categories ON
                lots.category_id = categories.id
            WHERE
                lots.finished_at > NOW()
            ORDER BY
                lots.finished_at DESC
            LIMIT 3
    ";
    $query = mysqli_query($connection, $sql);

    return mysqli_fetch_all($query, MYSQLI_ASSOC);
}

/**
 * Use to get Lot By ID
 * @return array - Lot info
 */
function fetchLotById(int $id): array
{
    global $connection;

    $sql_check_id = "
                SELECT
                    lots.id
                FROM
                    lots
                WHERE
                    lots.id = $id;
    ";

    $query_check_id = mysqli_query($connection, $sql_check_id);

    $result_check_id = mysqli_fetch_array($query_check_id, MYSQLI_ASSOC);

    if ($result_check_id) {
        $sql = "
                SELECT
                    lots.title,
                    lots.description,
                    lots.start_price AS price,
                    lots.bet_range,
                    lots.image_url AS image,
                    lots.finished_at,
                    categories.title AS category
                FROM
                    lots
                JOIN categories ON
                    lots.category_id = categories.id
                WHERE
                    lots.id = $id
        ";
        $query = mysqli_query($connection, $sql);

        return mysqli_fetch_array($query, MYSQLI_ASSOC);
    } else {
        return [];
    }
}


/**
 * Use to get values from POST request
 */

 function fetchPostValue(string $name) {
    if (isset($_POST)) {
        return $_POST[$name] ?? '';
    } else return '';
 }

 /**
  * Use to get file PATH 
  */

function fetchFilePath(string $name): string
{
    if (isset($_FILES[$name])) {
        return $_FILES[$name]['full_path'] ?? '';
    } else return '';
}