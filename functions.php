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
