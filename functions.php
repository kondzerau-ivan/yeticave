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
 * Возвращает список категорий
 * @param mysqli $con Ресурс подключения к базе данных
 * @return array Массив категорий
 */
function fetchCategories($con): array
{
    $sql = '
        SELECT code, name
        FROM categories;
    ';

    $result = mysqli_query($con, $sql);
    if (!$result) {
        throw new Exception("Ошибка запроса: " . mysqli_error($con));
    }
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}
