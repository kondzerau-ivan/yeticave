<?php
function validateEmail($name)
{
    if (!filter_input(INPUT_POST, $name, FILTER_VALIDATE_EMAIL)) {
        return "Введите корректный email";
    }
}

function validateFilled($name)
{
    if (empty($_POST[$name])) {
        return "Это поле должно быть заполнено";
    }
}

function isCorrectLength($name, $min, $max)
{
    $len = strlen($_POST[$name]);

    if ($len < $min or $len > $max) {
        return "Значение должно быть от $min до $max символов";
    }
}

$rules = [
    'lot-name' => fn() => validateFilled('lot-name'),
    'category' => fn() => validateFilled('category'),
    'message' => fn() => validateFilled('message'),
    'image' => fn() => validateFilled('image'),
    'lot-rate' => fn() => validateFilled('lot-rate'),
    'lot-step' => fn() => validateFilled('lot-step'),
    'lot-date' => fn() => validateFilled('lot-date')
];
