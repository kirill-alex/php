<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8" />
  <title>Решение уравнения</title>
</head>
<body>

<h1>Уравнение: X / 8 = 6</h1>

<?php

$operator   = "/";
$B          = 8;
$C          = 6;
$x_position = "left";

$operator_name = match($operator) {
    "+" => "сложение",
    "-" => "вычитание",
    "*" => "умножение",
    "/" => "деление",
};

// X / B = C  =>  X = C * B
$result = match($operator) {
    "+" => $C - $B,
    "-" => $C + $B,
    "*" => $C / $B,
    "/" => $C * $B,
};

$solution_formula = match($operator) {
    "+" => "X = $C - $B",
    "-" => "X = $C + $B",
    "*" => "X = $C / $B",
    "/" => "X = $C * $B",
};

$check      = $result / $B;
$is_correct = ($check == $C);

echo "<p>Оператор: " . $operator . " (" . $operator_name . ")</p>";
echo "<p>Расположение X: слева от оператора</p>";
echo "<p>Формула: " . $solution_formula . "</p>";
echo "<p><strong>X = " . $result . "</strong></p>";
echo "<p>Проверка: " . $result . " / " . $B . " = " . $check . " — " . ($is_correct ? "верно" : "ошибка") . "</p>";

?>

</body>
</html>