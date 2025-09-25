<?php
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/functions.php';

/**
 * Проверяет, что поле заполнено в POST-запросе.
 *
 * @param string $name Имя поля
 * @return string|null Сообщение об ошибке или null
 */
function validateFilled(string $value): ?string
{
    if (trim($value) === '') {
        return "Это поле должно быть заполнено.";
    }

    return null;
}

/**
 * TODO: Написать функцию проверку существования категории
 */

function validateCategory(string $id, array $list): ?string
{
    if (!in_array($id, $list)) {
        return "Нужно указать существующую категорию.";
    }

    return null;
}

/**
 * Проверяет корректность email в POST-запросе.
 *
 * @param string $name Имя поля
 * @return string|null Сообщение об ошибке или null
 */
function validateEmail(string $name): ?string
{
    if (!filter_input(INPUT_POST, $name, FILTER_VALIDATE_EMAIL)) {
        return "Введите корректный email.";
    }

    return null;
}

/**
 * Проверяет длину строки в поле POST-запроса.
 *
 * @param string $name Имя поля
 * @param int $min Минимальная длина
 * @param int $max Максимальная длина
 * @return string|null Сообщение об ошибке или null
 */
function validateLenght(string $lot_name, int $min, int $max): ?string
{
    $lenght = strlen($lot_name);
    if ($lenght < $min or $lenght > $max) {
        return "Значение должно быть от $min до $max символов.";
    }

    return null;
}

/**
 * Проверяет, что цена больше нуля.
 *
 * @param string $name Имя поля
 * @return string|null Сообщение об ошибке или null
 */
function validatePrice(string $lot_rate): ?string
{
    $price = intval($lot_rate);

    if ($price <= 0) {
        return "Начальная цена должна быть больше нуля.";
    }

    return null;
}

/**
 * Проверяет, что шаг ставки больше нуля.
 *
 * @param string $name Имя поля
 * @return string|null Сообщение об ошибке или null
 */
function validatePriceStep(string $lot_step): ?string
{
    $step = intval($lot_step);
    if ($step <= 0) {
        return 'Шаг ставки должен быть больше нуля.';
    }

    return null;
}

/**
 * Проверяет, что дата больше текущей хотя бы на один день.
 *
 * @param string $name Имя поля
 * @return string|null Сообщение об ошибке или null
 */
function validateDate(string $lot_date): ?string
{
    [$hours] = getDateRange($lot_date);
    if ($hours < 24) {
        return "Указанная дата должна быть больше текущей даты, хотя бы на один день";
    }

    return null;
}

/**
 * Проверяет формат даты (ГГГГ-ММ-ДД).
 *
 * @param string $name Имя поля
 * @return string|null Сообщение об ошибке или null
 */
function validateDateFormat(string $lot_date): ?string
{
    if (!is_date_valid($lot_date)) {
        return "Дата должна иметь формат: «ГГГГ-ММ-ДД».";
    }

    return null;
}

function validateFile(array $file_data): ?string
{
    if (!empty($file_data['name'])) {
        $fallowed = ['image/jpeg', 'image/png'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $ftype = finfo_file($finfo, $file_data['tmp_name']);
        finfo_close($finfo);

        if (!in_array($ftype, $fallowed, true)) {
            return "Файл должен быть изображением (jpeg/png).";
        } else {
            return null;
        }
    } else {
        return "Загрузка изображения обязательна.";
    }
}
