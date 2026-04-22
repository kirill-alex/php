<?php require 'back.php'; ?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8"/>
  <title>Калькулятор</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>
<div class="calc">
  <h2>Калькулятор</h2>

  <!-- Поле пользовательского ввода (заполняется JS) -->
  <input type="text" style="color: <?= $displayError ? '#c00' : '#000' ?>" id="display" readonly placeholder="Введите выражение"/>

  <!-- Поле вывода результата (заполняется из GET-параметра) -->
  <input type="text" id="result-display" readonly
    value="<?= $displayError ?? ($displayResult !== null ? '= ' . $displayResult : '') ?>"/>

  <!-- Скрытая форма для POST -->
  <form class="hidden-form" id="calc-form" method="POST" action="">
    <input type="hidden" name="expression" id="expression-input"/>
  </form>

  <div class="buttons">
    <!-- Цифры и скобки -->
    <button onclick="press('7')">7</button>
    <button onclick="press('8')">8</button>
    <button onclick="press('9')">9</button>
    <button class="op" onclick="press('/')">/</button>

    <button onclick="press('4')">4</button>
    <button onclick="press('5')">5</button>
    <button onclick="press('6')">6</button>
    <button class="op" onclick="press('*')">*</button>

    <button onclick="press('1')">1</button>
    <button onclick="press('2')">2</button>
    <button onclick="press('3')">3</button>
    <button class="op" onclick="press('-')">−</button>

    <button onclick="press('0')">0</button>
    <button class="op" onclick="press('(')">(</button>
    <button class="op" onclick="press(')')">)</button>
    <button class="op" onclick="press('+')">+</button>

    <!-- Управление -->
    <button class="clear" onclick="clearDisplay()">C</button>
    <button class="equals" onclick="calculate()">=</button>
  </div>
</div>

<script>
  const display = document.getElementById('display');
  const resultDisplay = document.getElementById('result-display');

  // Восстанавливаем выражение из GET после редиректа
  <?php if ($displayExpr): ?>
  display.value = <?= json_encode($displayExpr) ?>;
  <?php endif; ?>

  function press(char) {
    display.value += char;
  }

  function clearDisplay() {
    display.value = '';
    resultDisplay.value = '';
    // убираем GET-параметры из URL без перезагрузки
    history.replaceState(null, '', window.location.pathname);
  }

  function calculate() {
    const expr = display.value.trim();
    if (!expr) return;
    document.getElementById('expression-input').value = expr;
    document.getElementById('calc-form').submit();
  }
</script>
</body>
</html>