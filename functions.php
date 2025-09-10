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
