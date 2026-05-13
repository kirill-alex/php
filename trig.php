<?php
// ──────────────────────────────────────────────────────────────────
// trig.php — тригонометрические функции
//
// calcTrig() принимает имя функции строкой и аргумент в градусах,
// вызывает нужную PHP-функцию через символическую ссылку call_user_func.
//
// callTrig() — обёртка для парсера: принимает радианы, делегирует в calcTrig.
// ──────────────────────────────────────────────────────────────────

/**
 * Вычислить тригонометрическую функцию по имени и аргументу в градусах.
 * Символическая ссылка: имя функции передаётся строкой через call_user_func.
 *
 * @param string $funcName  sin | cos | tan | asin | acos | atan
 * @param float  $degrees   Аргумент в градусах
 * @return float
 */
function calcTrig(string $funcName, float $degrees): float
{
    $supported = ['sin', 'cos', 'tan', 'asin', 'acos', 'atan'];

    if (!in_array($funcName, $supported, true)) {
        throw new InvalidArgumentException(
            "Неизвестная тригонометрическая функция: $funcName. "
            . "Допустимы: " . implode(', ', $supported)
        );
    }

    $radians = deg2rad($degrees);

    // Валидация перед вызовом
    if ($funcName === 'tan' && abs(cos($radians)) < 1e-12) {
        throw new RuntimeException("tan($degrees) не определён — деление на ноль");
    }
    if (in_array($funcName, ['asin', 'acos']) && ($degrees < -1.0 || $degrees > 1.0)) {
        throw new RuntimeException("$funcName($degrees) не определён — аргумент вне [-1; 1]");
    }

    // Символическая ссылка — вызов функции по строковому имени
    return call_user_func($funcName, $radians);
}

/**
 * Обёртка для парсера калькулятора.
 * Парсер работает в радианах — переводим в градусы и передаём в calcTrig.
 *
 * @param string $funcName  Имя тригонометрической функции
 * @param float  $radians   Аргумент в радианах
 * @return float
 */
function callTrig(string $funcName, float $radians): float
{
    return calcTrig($funcName, rad2deg($radians));
}