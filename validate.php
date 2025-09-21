<?php
function validateEmail($name)
{
    if (!filter_input(INPUT_POST, $name, FILTER_VALIDATE_EMAIL)) {
        return "Введите корректный email.";
    }
}

function validateFilled($name)
{
    $value = $_POST[$name] ?? '';

    if (trim($value) === '') {
        return "Это поле должно быть заполнено.";
    }
}

function isCorrectLength($name, $min, $max)
{
    $len = strlen($_POST[$name]);

    if ($len < $min or $len > $max) {
        return "Значение должно быть от $min до $max символов.";
    }
}

function isCorrectPrice($name)
{
    $price = intval($_POST[$name]);

    if ($price <= 0) {
        return "Значение должно быть больше нуля.";
    }
}

$rules = [
    'lot-name' => fn($key) => [
        validateFilled($key)
    ],
    'category' => fn($key) => [
        validateFilled($key)
    ],
    'message' => fn($key) => [
        validateFilled($key)
    ],
    'lot-rate' => fn($key) => [
        validateFilled($key),
        isCorrectPrice($key)
    ],
    'lot-step' => fn($key) => [
        validateFilled($key)
    ],
    'lot-date' => fn($key) => [
        validateFilled($key)
    ]
];
