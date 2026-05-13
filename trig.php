<?php

/**
 * Вычислить тригонометрическое выражение по имени функции и аргументу.
 *
 * @param  string $funcName  Имя тригонометрической функции: sin | cos | tan | asin | acos | atan
 * @param  float  $degrees   Аргумент в ГРАДУСАХ
 * @return float             Результат вычисления
 * @throws InvalidArgumentException  Если функция не поддерживается
 * @throws RuntimeException          Если значение не определено (tan 90°)
 */
function calcTrig(string $funcName, float $degrees): float
{
    // Таблица допустимых имён: строковое имя → локальная обёртка
    $supported = ['sin', 'cos', 'tan', 'asin', 'acos', 'atan'];

    if (!in_array($funcName, $supported, true)) {
        throw new InvalidArgumentException(
            "Неизвестная тригонометрическая функция: «{$funcName}». "
            . "Допустимы: " . implode(', ', $supported)
        );
    }

    $radians = deg2rad($degrees);

    // ── Символическая ссылка ──────────────────────────────────────
    // Имя функции хранится в переменной $funcName.
    // PHP вызывает функцию по этому строковому имени через call_user_func.
    // Альтернативная запись через variable function:  $result = $funcName($radians);
    // ─────────────────────────────────────────────────────────────
    switch ($funcName) {
        case 'tan':
            // cos(x) ≈ 0  →  tan не определён
            if (abs(cos($radians)) < 1e-12) {
                throw new RuntimeException(
                    "tan({$degrees}°) не определён — деление на ноль"
                );
            }
            break;
        case 'asin':
        case 'acos':
            if ($degrees < -1.0 || $degrees > 1.0) {
                throw new RuntimeException(
                    "{$funcName}({$degrees}) не определён — аргумент вне [-1; 1]"
                );
            }
            break;
    }

    // Символическая ссылка: вызов встроенной PHP-функции по строковому имени
    $result = call_user_func($funcName, $radians);

    return $result;
}


// ──────────────────────────────────────────────────────────────────
// Обёртки для парсера калькулятора
// Принимают аргумент УЖЕ в радианах (парсер работает в радианах).
// Делегируют вычисление в calcTrig() через deg2rad→rad2deg конвертацию.
// ──────────────────────────────────────────────────────────────────


function callTrig(string $funcName, float $radians): float
{
    return calcTrig($funcName, rad2deg($radians));
}