<?php

/**
 * Форматирует цену лота - разделяет пробелом разряды числа, добавляет знак рубля
 * @param integer $price Цена лота
 * @return string Итоговая цена после форматирования
 */

function formatPrice(int $price): string
{
    return number_format($price, 0, '', ' ') . ' ₽';
}

/**
 * Возвращает оставшееся время лота
 * @param string $expiration_date Дата окончания в формате YYYY-MM-DD
 * @return array Целое количество часов до даты и остаток в минутах
 */

function getDateRange(string $expiration_date): array
{
    $current_date = date_create('now');
    $expiration_date = date_create($expiration_date);
    $life_time = date_diff($current_date, $expiration_date);

    if ($life_time->invert) {
        return [0, 0];
    }

    $hours = $life_time->days * 24 + $life_time->h;
    $minutes = $life_time->i;

    return [$hours, $minutes];
}

function getBetDateRange(string $created_at): array
{
    $current_date = date_create('now');
    $created_at = date_create($created_at);
    $life_time = date_diff($created_at, $current_date);

    if ($life_time->invert) {
        return [0, 0];
    }

    $hours = $life_time->days * 24 + $life_time->h;
    $minutes = $life_time->i;

    return [$hours, $minutes];
}

/**
 * Возвращает 6 новых лотов
 * @param mysqli $con Ресурс подключения к базе данных
 * @return array Массив лотов
 */
function fetchLots(mysqli $con): array
{
    $sql = '
        SELECT
        l.id,
        l.name AS lot_name,
        l.price AS start_price,
        l.image,
        l.expiration_date,
        c.name AS category_name,
        COUNT(b.id) AS bet_count
        FROM lots AS l
        JOIN categories AS c ON c.id = l.category_id
        LEFT JOIN bets AS b ON b.lot_id = l.id
        WHERE l.expiration_date > NOW()
        GROUP BY l.id
        ORDER BY l.created_at DESC
        LIMIT 6;
    ';
    $result = mysqli_query($con, $sql);
    if (!$result) {
        throw new Exception("Ошибка запроса: " . mysqli_error($con));
    }
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/**
 * Проверяет, существует ли лот с заданным ID
 * @param mysqli $con Ресурс подключения к базе данных
 * @param int $id Предполагаемый ID лота
 * @return bool Результат запроса
 */

function lotExistsById(mysqli $con, int $id): bool
{
    $sql = '
        SELECT 1
        FROM lots
        WHERE id = ?
        LIMIT 1;
    ';

    $stmt = mysqli_prepare($con, $sql);
    if (!$stmt) {
        throw new Exception("Ошибка подготовки запроса: " . mysqli_error($con));
    }

    mysqli_stmt_bind_param($stmt, 'i', $id);

    if (!mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        throw new Exception("Ошибка выполнения запроса: " . mysqli_error($con));
    }

    mysqli_stmt_bind_result($stmt, $exists);
    $result = mysqli_stmt_fetch($stmt);

    mysqli_stmt_close($stmt);

    return (bool) $result;
}


/**
 * Возвращает лот по его ID
 * @param mysqli $con Ресурс подключения к базе данных
 * @param int $id Идентификатор лота
 * @return array|null Информация о лоте или null, если не найден
 */

function fetchLotById(mysqli $con, int $id): array|null
{
    $sql = '
        SELECT 
        l.id,
        l.author_id,
        l.name AS lot_name,
        l.price AS start_price,
        l.image,
        l.description,
        l.expiration_date,
        l.step,
        c.name AS category_name
        FROM lots AS l
        JOIN categories AS c
        ON c.id = l.category_id
        WHERE l.id = ?
        LIMIT 1;
    ';

    $stmt = mysqli_prepare($con, $sql);
    if (!$stmt) {
        throw new Exception("Ошибка подготовки запроса: " . mysqli_error($con));
    }

    mysqli_stmt_bind_param($stmt, 'i', $id);
    if (!mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        throw new Exception("Ошибка выполнения запроса: " . mysqli_error($con));
    }

    $result = mysqli_stmt_get_result($stmt);
    if (!$result) {
        throw new Exception("Ошибка выполнения: " . mysqli_error($con));
    }

    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    return $row ?: null;
}

function fetchCategoryById(mysqli $con, int $id): array|null
{
    $sql = '
        SELECT *
        FROM categories AS c
        WHERE c.id = ?
        LIMIT 1;
    ';

    $stmt = mysqli_prepare($con, $sql);
    if (!$stmt) {
        throw new Exception("Ошибка подготовки запроса: " . mysqli_error($con));
    }

    mysqli_stmt_bind_param($stmt, 'i', $id);
    if (!mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        throw new Exception("Ошибка выполнения запроса: " . mysqli_error($con));
    }

    $result = mysqli_stmt_get_result($stmt);
    if (!$result) {
        throw new Exception("Ошибка выполнения: " . mysqli_error($con));
    }

    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    return $row ?: null;
}

/**
 * Возвращает список категорий
 * @param mysqli $con Ресурс подключения к базе данных
 * @return array Массив категорий
 */
function fetchCategories(mysqli $con): array
{
    $sql = '
        SELECT *
        FROM categories;
    ';

    $result = mysqli_query($con, $sql);
    if (!$result) {
        throw new Exception("Ошибка запроса: " . mysqli_error($con));
    }
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function fetchBetsHistory(mysqli $con, int $lotId): array
{
    $sql = '
        SELECT b.created_at, b.amount, u.name AS user_name
        FROM bets AS b
        JOIN lots AS l
        ON l.id = b.lot_id
        JOIN users AS u
        ON u.id = b.user_id
        WHERE l.id = ?
        ORDER BY b.created_at DESC;
    ';

    $stmt = mysqli_prepare($con, $sql);
    if (!$stmt) {
        throw new Exception("Ошибка подготовки запроса: " . mysqli_error($con));
    }

    mysqli_stmt_bind_param($stmt, 'i', $lotId);

    if (!mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        throw new Exception("Ошибка выполнения запроса: " . mysqli_stmt_error($stmt));
    }

    $result = mysqli_stmt_get_result($stmt);

    if (!$result) {
        throw new Exception("Ошибка выполнения: " . mysqli_error($con));
    }

    $history = mysqli_fetch_all($result, MYSQLI_ASSOC);

    mysqli_stmt_close($stmt);

    return $history ?: [];
}

function fetchAllUserBets(mysqli $con, int $userId): array
{
    $sql = '
        SELECT
            l.name AS lot_name,
            l.image AS lot_image,
            u.contacts,
            c.name AS category_name,
            l.expiration_date,
            b.amount,
            b.created_at,
            (l.expiration_date < NOW()) AS is_finished,
            (l.expiration_date < NOW() AND b.created_at = (
                SELECT MAX(b2.created_at)
                FROM bets AS b2
                WHERE b2.lot_id = b.lot_id
            )) AS is_winner
        FROM bets AS b
        JOIN lots AS l ON l.id = b.lot_id
        JOIN categories AS c ON c.id = l.category_id
        JOIN users AS u ON u.id = b.user_id
        WHERE b.user_id = ?
        ORDER BY b.created_at DESC;
    ';

    $stmt = mysqli_prepare($con, $sql);
    if (!$stmt) {
        throw new Exception("Ошибка подготовки запроса: " . mysqli_error($con));
    }

    mysqli_stmt_bind_param($stmt, 'i', $userId);

    if (!mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        throw new Exception("Ошибка выполнения запроса: " . mysqli_stmt_error($stmt));
    }

    $result = mysqli_stmt_get_result($stmt);

    if (!$result) {
        throw new Exception("Ошибка выполнения: " . mysqli_error($con));
    }

    $bets = mysqli_fetch_all($result, MYSQLI_ASSOC);

    mysqli_stmt_close($stmt);

    return $bets ?: [];
}

/**
 * Возвращает данные Лота из массива $POST
 * @param string $name Имя поля
 * @return array Значение поля или пустая строка
 */
function getLotPostData(): array
{
    return [
        'lot-name' => $_POST['lot-name'] ?? '',
        'message' => $_POST['message'] ?? '',
        'image' => $_FILES['image'] ?? [],
        'lot-rate' => $_POST['lot-rate'] ?? '',
        'lot-date' => $_POST['lot-date'] ?? '',
        'lot-step' => $_POST['lot-step'] ?? '',
        'category' => $_POST['category'] ?? '',
        'author_id' => 1
    ];
}

function getUserPostData(): array
{
    return [
        'email' => $_POST['email'] ?? '',
        'password' => $_POST['password'] ?? '',
        'name' => $_POST['name'] ?? '',
        'message' => $_POST['message'] ?? ''
    ];
}

function getLoginPostData(): array
{
    return [
        'email' => $_POST['email'] ?? '',
        'password' => $_POST['password'] ?? '',
    ];
}

function renderErrorsMessage(array $errors, string $name): string
{
    if (!isset($errors[$name])) {
        return '';
    }

    $messages = (array) $errors[$name];

    return implode('<br>', array_map('htmlspecialchars', $messages));
}

function fileUpload(): string
{
    [$fname, $fext] = explode('.', $_FILES['image']['name']);
    $fname = $fname . '__' . uniqid() . '.' . $fext;
    $fpath = __DIR__ . '/uploads/';
    move_uploaded_file($_FILES['image']['tmp_name'], $fpath . $fname);
    return $fname;
}

function addNewLot(mysqli $con, array $lot): int
{
    $name = $lot['lot-name'];
    $description = $lot['message'];
    $image = fileUpload();
    $price = $lot['lot-rate'];
    $expiration_date = $lot['lot-date'];
    $step = $lot['lot-step'];
    $category_id = $lot['category'];
    $author_id = $_SESSION['user']['id'];

    $sql = "
        INSERT INTO lots (
            name,
            description,
            image,
            price,
            expiration_date,
            step,
            author_id,
            category_id
        )
        VALUES (
            ?, ?, ?, ?, ?, ?, ?, ?
        )
    ";

    $stmt = mysqli_prepare($con, $sql);
    if (!$stmt) {
        throw new Exception("Ошибка подготовки запроса: " . mysqli_error($con));
    }

    mysqli_stmt_bind_param(
        $stmt,
        'sssisiii',
        $name,
        $description,
        $image,
        $price,
        $expiration_date,
        $step,
        $author_id,
        $category_id
    );

    if (!mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        throw new Exception("Ошибка выполнения запроса: " . mysqli_error($con));
    }

    mysqli_stmt_close($stmt);

    return mysqli_insert_id($con);
}

function addNewBet(mysqli $con, int $amount, int $userId, int $lotId): void
{
    mysqli_begin_transaction($con);

    try {
        $addNewBet = '
            INSERT INTO bets (amount, user_id, lot_id) VALUES (?, ?, ?);
        ';

        $stmt = mysqli_prepare($con, $addNewBet);

        if (!$stmt) {
            throw new Exception("Ошибка подготовки запроса: " . mysqli_error($con));
        }

        mysqli_stmt_bind_param(
            $stmt,
            'iii',
            $amount,
            $userId,
            $lotId
        );

        if (!mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            throw new Exception("Ошибка выполнения запроса: " . mysqli_error($con));
        }

        mysqli_stmt_close($stmt);

        $updateLotPrice = '
            UPDATE lots
            SET price = ?
            WHERE id = ?;
        ';

        $stmt = mysqli_prepare($con, $updateLotPrice);

        if (!$stmt) {
            throw new Exception("Ошибка подготовки запроса: " . mysqli_error($con));
        }

        mysqli_stmt_bind_param(
            $stmt,
            'ii',
            $amount,
            $lotId
        );

        if (!mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            throw new Exception("Ошибка выполнения запроса: " . mysqli_error($con));
        }

        mysqli_stmt_close($stmt);

        mysqli_commit($con);
    } catch (Exception $e) {
        mysqli_rollback($con);
        throw $e;
    }
}

function addNewUser(mysqli $con, array $user): void
{
    $email = $user['email'];
    $password = password_hash($user['password'], PASSWORD_DEFAULT);
    $name = $user['name'];
    $contacts = $user['message'];

    $sql = "
        INSERT INTO users (
            email,
            password,
            name,
            contacts
        )
        VALUES (
            ?, ?, ?, ?
        )
    ";

    $stmt = mysqli_prepare($con, $sql);
    if (!$stmt) {
        throw new Exception("Ошибка подготовки запроса: " . mysqli_error($con));
    }

    mysqli_stmt_bind_param(
        $stmt,
        'ssss',
        $email,
        $password,
        $name,
        $contacts
    );

    if (!mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        throw new Exception("Ошибка выполнения запроса: " . mysqli_error($con));
    }

    mysqli_stmt_close($stmt);
}

function getUserByEmail(mysqli $con, string $email): array|null
{
    $sql = '
        SELECT *
        FROM users
        WHERE users.email = ?
        LIMIT 1;
    ';

    $stmt = mysqli_prepare($con, $sql);
    if (!$stmt) {
        throw new Exception("Ошибка подготовки запроса: " . mysqli_error($con));
    }

    mysqli_stmt_bind_param($stmt, 's', $email);
    if (!mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        throw new Exception("Ошибка выполнения запроса: " . mysqli_error($con));
    }

    $result = mysqli_stmt_get_result($stmt);
    if (!$result) {
        throw new Exception("Ошибка выполнения: " . mysqli_error($con));
    }

    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    return $row ?: null;
}

function getSearchData(): string
{
    if (isset($_GET['search'])) {
        return htmlspecialchars($_GET['search']);
    }
    return '';
}

function getCountLots(mysqli $con, string $words): int
{
    $sql = '
        SELECT
            l.name AS lot_name,
            l.image,
            l.price,
            l.step,
            l.expiration_date,
            c.name AS category_name,
            COUNT(b.id) AS bet_count
        FROM lots AS l
        JOIN categories AS c ON c.id = l.category_id
        LEFT JOIN bets AS b ON b.lot_id = l.id
        WHERE MATCH(l.name, l.description) AGAINST(?)
        GROUP BY l.id
    ';

    $stmt = mysqli_prepare($con, $sql);
    if (!$stmt) {
        throw new Exception("Ошибка подготовки запроса: " . mysqli_error($con));
    }

    mysqli_stmt_bind_param($stmt, 's', $words);
    if (!mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        throw new Exception("Ошибка выполнения запроса: " . mysqli_error($con));
    }

    $result = mysqli_stmt_get_result($stmt);
    if (!$result) {
        throw new Exception("Ошибка выполнения: " . mysqli_error($con));
    }

    $lots = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);

    return count($lots);
}

function findLots(mysqli $con, string $words, $limit = 1, $offset = 0): array
{
    $sql = '
        SELECT
            l.id AS lot_id,
            l.name AS lot_name,
            l.image,
            l.price,
            l.step,
            l.expiration_date,
            c.name AS category_name,
            COUNT(b.id) AS bet_count
        FROM lots AS l
        JOIN categories AS c ON c.id = l.category_id
        LEFT JOIN bets AS b ON b.lot_id = l.id
        WHERE MATCH(l.name, l.description) AGAINST(?)
        GROUP BY l.id
        ORDER BY l.expiration_date DESC LIMIT ? OFFSET ?;
    ';

    $stmt = mysqli_prepare($con, $sql);
    if (!$stmt) {
        throw new Exception("Ошибка подготовки запроса: " . mysqli_error($con));
    }

    mysqli_stmt_bind_param($stmt, 'sii', $words, $limit, $offset);
    if (!mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        throw new Exception("Ошибка выполнения запроса: " . mysqli_error($con));
    }

    $result = mysqli_stmt_get_result($stmt);
    if (!$result) {
        throw new Exception("Ошибка выполнения: " . mysqli_error($con));
    }

    $lots = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);

    return $lots ?: [];
}

function getCountLotsByCategory(mysqli $con, int $id): int
{
    $sql = '
        SELECT l.id
        FROM lots AS l
        JOIN categories AS c ON l.category_id = c.id
        WHERE c.id = ?;
    ';

    $stmt = mysqli_prepare($con, $sql);
    if (!$stmt) {
        throw new Exception("Ошибка подготовки запроса: " . mysqli_error($con));
    }

    mysqli_stmt_bind_param($stmt, 'i', $id);
    if (!mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        throw new Exception("Ошибка выполнения запроса: " . mysqli_error($con));
    }

    $result = mysqli_stmt_get_result($stmt);
    if (!$result) {
        throw new Exception("Ошибка выполнения: " . mysqli_error($con));
    }

    $lots = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);

    return count($lots);
}

function findLotsByCategory(mysqli $con, int $id, int $limit = 1, int $offset = 0): array
{
    $sql = '
        SELECT
            l.id AS lot_id,
            l.name AS lot_name,
            l.image,
            l.price,
            l.expiration_date,
            c.name AS category_name,
            COUNT(b.id) AS bet_count
        FROM lots AS l
        JOIN categories AS c ON c.id = l.category_id
        LEFT JOIN bets AS b ON l.id = b.lot_id
        WHERE l.category_id = ?
        GROUP BY l.id
        ORDER BY l.created_at DESC
        LIMIT ?
        OFFSET ?;
    ';

    $stmt = mysqli_prepare($con, $sql);
    if (!$stmt) {
        throw new Exception("Ошибка подготовки запроса: " . mysqli_error($con));
    }

    mysqli_stmt_bind_param($stmt, 'iii', $id, $limit, $offset);
    if (!mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        throw new Exception("Ошибка выполнения запроса: " . mysqli_error($con));
    }

    $result = mysqli_stmt_get_result($stmt);
    if (!$result) {
        throw new Exception("Ошибка выполнения: " . mysqli_error($con));
    }

    $lots = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);

    return $lots ?: [];
}
