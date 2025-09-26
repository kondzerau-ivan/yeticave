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
 * @param string $value Значение для валидации
 * @return string|null Сообщение об ошибке или null
 */
function validateEmail(string $value): ?string
{
    if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
        return "Введите корректный email.";
    }

    return null;
}

function validateUniqueEmail(mysqli $con, string $value): ?string
{
    $sql = '
        SELECT 1
        FROM users
        WHERE email = ?
        LIMIT 1;
    ';

    $stmt = mysqli_prepare($con, $sql);
    if (!$stmt) {
        throw new Exception("Ошибка подготовки запроса: " . mysqli_error($con));
    }

    mysqli_stmt_bind_param($stmt, 's', $value);

    if (!mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        throw new Exception("Ошибка выполнения запроса: " . mysqli_error($con));
    }

    mysqli_stmt_bind_result($stmt, $exists);
    $result = (bool) mysqli_stmt_fetch($stmt);

    if ($result) {
        return 'Пользователь с таким e-mail уже существует.';
    }

    return null;
}

function validateUniqueName($con, $value): ?string
{
    $sql = '
        SELECT 1
        FROM users
        WHERE name = ?
        LIMIT 1;
    ';

    $stmt = mysqli_prepare($con, $sql);
    if (!$stmt) {
        throw new Exception("Ошибка подготовки запроса: " . mysqli_error($con));
    }

    mysqli_stmt_bind_param($stmt, 's', $value);

    if (!mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        throw new Exception("Ошибка выполнения запроса: " . mysqli_error($con));
    }

    mysqli_stmt_bind_result($stmt, $exists);
    $result = (bool) mysqli_stmt_fetch($stmt);

    if ($result) {
        return 'Пользователь с таким именем уже существует.';
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
function validateLenght(string $lot_name, int $min = 0, int $max = 0): ?string
{
    $lenght = strlen($lot_name);
    if ($min && $max) {
        if ($lenght < $min or $lenght > $max) {
            return "Значение должно быть от $min до $max символов.";
        }
    }

    if ($min && !$max) {
        if ($lenght < $min) {
            return "Значение должно быть от $min символов.";
        }
    }

    if ($max && !$min) {
        if ($lenght < $min) {
            return "Значение должно быть до $max символов.";
        }
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
