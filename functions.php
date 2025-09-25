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
        c.name AS category_name
        FROM lots AS l
        JOIN categories AS c
        ON c.id = l.category_id
        WHERE l.expiration_date > NOW()
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
        'author_id' => 1,
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

function addNewLot(mysqli $con): int
{
    $name = $_POST['lot-name'];
    $description = $_POST['message'];
    $image = fileUpload();
    $price = $_POST['lot-rate'];
    $expiration_date = $_POST['lot-date'];
    $step = $_POST['lot-step'];
    $category_id = $_POST['category'];
    $author_id = 1;

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
