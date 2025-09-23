<?php
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/functions.php';

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
 * Проверяет, что поле заполнено в POST-запросе.
 *
 * @param string $name Имя поля
 * @return string|null Сообщение об ошибке или null
 */
function validateFilled(string $name): ?string
{
    $value = $_POST[$name] ?? '';

    if (trim($value) === '') {
        return "Это поле должно быть заполнено.";
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
function isCorrectLength(string $name, int $min, int $max): ?string
{
    $len = strlen($_POST[$name]);

    if ($len < $min or $len > $max) {
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
function isCorrectPrice(string $name): ?string
{
    $price = intval($_POST[$name]);

    if ($price <= 0) {
        return "Значение должно быть больше нуля.";
    }

    return null;
}

/**
 * Проверяет, что шаг ставки больше нуля.
 *
 * @param string $name Имя поля
 * @return string|null Сообщение об ошибке или null
 */
function isCorrectPriceStep(string $name): ?string
{
    $step = intval($_POST[$name]);
    if ($step <= 0) {
        return 'Шаг ставки должен быть целым числом больше нуля.';
    }

    return null;
}

/**
 * Проверяет, что дата больше текущей хотя бы на один день.
 *
 * @param string $name Имя поля
 * @return string|null Сообщение об ошибке или null
 */
function isDateValid(string $name): ?string
{
    $date = $_POST[$name] ?? '';
    [$hours] = getDateRange($date);
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
function isDateFormatValid(string $name): ?string
{
    $date = $_POST[$name] ?? '';
    if (!is_date_valid($date)) {
        return "Дата должна иметь формат: «ГГГГ-ММ-ДД».";
    }

    return null;
}

/**
 * Проверяет тип загружаемого файла
 * 
 * @param string $name Имя поля
 * @return string|null Сообщение об ошибке или null
 */
function validateFile(string $name): ?string
{
    $allowed = ['image/jpeg', 'image/png'];
    if (isset($_FILES['image']) && is_uploaded_file($_FILES[$name]['tmp_name'])) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $type = finfo_file($finfo, $_FILES[$name]['tmp_name']) ?? '';
        finfo_close($finfo);

        if (!in_array($type, $allowed, true)) {
            return "Файл должен быть изображением.";
        } else {
            return null;
        }
    } else {
        return "Загрузка изображения является обязательной.";
    }
}

$rules = [
    'lot-name' => fn($key) => [
        validateFilled($key),
        isCorrectLength($key, 10, 100)
    ],
    'category' => fn($key) => [
        validateFilled($key)
    ],
    'message' => fn($key) => [
        validateFilled($key)
    ],
    'image' => fn($key) => [
        validateFile($key)
    ],
    'lot-rate' => fn($key) => [
        validateFilled($key),
        isCorrectPrice($key)
    ],
    'lot-step' => fn($key) => [
        validateFilled($key),
        isCorrectPriceStep($key)
    ],
    'lot-date' => fn($key) => [
        validateFilled($key),
        isDateFormatValid($key),
        isDateValid($key)
    ]
];
