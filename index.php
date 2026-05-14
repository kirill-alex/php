<?php require 'back.php'; ?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Калькулятор</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@300;400;500&family=Space+Grotesk:wght@400;500;600&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="calc-wrap">
        <div class="calc">

            <div class="display-area">
                <div id="expr-line"></div>
                <input type="text" id="display" placeholder="0" autocomplete="off" spellcheck="false"
                    style="<?= $displayError ? 'color:#ef4444' : '' ?>" />
                <input type="text" id="result-display" readonly
                    value="<?= $displayError ? htmlspecialchars($displayError) : ($displayResult !== null ? '= ' . htmlspecialchars($displayResult) : '') ?>" />
            </div>

            <form id="calc-form" method="POST" action="" style="display:none">
                <input type="hidden" name="expression" id="expression-input" />
            </form>

            <div class="buttons">
                <!-- Row 1 -->
                <button class="fn" onclick="press('sin(')">sin</button>
                <button class="fn" onclick="press('cos(')">cos</button>
                <button class="fn" onclick="press('tan(')">tan</button>
                <button class="fn" onclick="press('ln(')">ln</button>
                <button class="fn" onclick="press('log(')">log</button>
                <!-- Row 2 -->
                <button class="fn" onclick="press('asin(')">asin</button>
                <button class="fn" onclick="press('acos(')">acos</button>
                <button class="fn" onclick="press('atan(')">atan</button>
                <button class="fn" onclick="press('^')">xʸ</button>
                <button class="fn" onclick="press('fact(')">x!</button>
                <!-- Row 3 -->
                <button class="fn" onclick="press('sqrt(')">√x</button>
                <button class="fn" onclick="press('cbrt(')">∛x</button>
                <button class="fn" onclick="press('pi')">π</button>
                <button class="fn" onclick="press('e')">e</button>
                <button class="del" onclick="deleteLast()">⌫</button>
                <!-- Row 4 -->
                <button onclick="press('7')">7</button>
                <button onclick="press('8')">8</button>
                <button onclick="press('9')">9</button>
                <button class="op" onclick="press('/')">÷</button>
                <button class="clear" onclick="clearDisplay()">C</button>
                <!-- Row 5 -->
                <button onclick="press('4')">4</button>
                <button onclick="press('5')">5</button>
                <button onclick="press('6')">6</button>
                <button class="op" onclick="press('*')">×</button>
                <button class="fn" onclick="press('abs(')">|x|</button>
                <!-- Row 6 -->
                <button onclick="press('1')">1</button>
                <button onclick="press('2')">2</button>
                <button onclick="press('3')">3</button>
                <button class="op" onclick="press('-')">−</button>
                <button class="op" onclick="press('+')">+</button>
                <!-- Row 7 -->
                <button class="zero" onclick="press('0')">0</button>
                <button onclick="press('.')">.</button>
                <button class="op" onclick="press('(')">(</button>
                <button class="op" onclick="press(')')">)</button>
                <button class="equals" onclick="calculate()">=</button>
            </div>

            <div class="hint">Клавиатура: цифры · + − * / ^ ( ) · Enter=вычислить · Esc=сброс · ⌫=удалить</div>
        </div>

        <div class="links">
            <a href="https://github.com/kirill-alex/php.git">GitHub</a>
            <a href="https://alexeev.wuaze.com/lab4/">Хостинг</a>
        </div>
    </div>

    <script>
        const display = document.getElementById('display');
        const resultDisp = document.getElementById('result-display');
        const exprLine = document.getElementById('expr-line');

        // Восстановление выражения после редиректа (GET)
        <?php if ($displayExpr): ?>
            display.value = <?= json_encode($displayExpr) ?>;
            exprLine.textContent = <?= json_encode($displayExpr) ?>;
        <?php endif; ?>

        function updateExprLine() {
            exprLine.textContent = display.value || '';
        }

        function press(char) {
            display.value += char;
            updateExprLine();
        }

        function deleteLast() {
            display.value = display.value.slice(0, -1);
            updateExprLine();
            resultDisp.value = '';
        }

        function clearDisplay() {
            display.value = '';
            resultDisp.value = '';
            exprLine.textContent = '';
            display.classList.remove('error');
            history.replaceState(null, '', window.location.pathname);
        }

        function calculate() {
            const expr = display.value.trim();
            if (!expr) return;
            exprLine.textContent = expr;
            document.getElementById('expression-input').value = expr;
            document.getElementById('calc-form').submit();
        }

        // Поддержка ввода с клавиатуры
        document.addEventListener('keydown', e => {
            if (e.key >= '0' && e.key <= '9') { press(e.key); return; }
            if (['+', '*', '/', '(', ')', '.', '^', '%'].includes(e.key)) { e.preventDefault(); press(e.key); return; }
            if (e.key === '-') { e.preventDefault(); press('-'); return; }
            if (e.key === 'Enter' || e.key === '=') { e.preventDefault(); calculate(); return; }
            if (e.key === 'Backspace') { e.preventDefault(); deleteLast(); return; }
            if (e.key === 'Escape') { clearDisplay(); return; }
            if (e.key === 'p') { press('pi'); return; }
            if (e.key === 'e' && !e.ctrlKey) { press('e'); return; }
            if (e.key === 's') { press('sin('); return; }
            if (e.key === 'c') { press('cos('); return; }
            if (e.key === 't') { press('tan('); return; }
            if (e.key === 'l') { press('ln('); return; }
            if (e.key === 'L') { press('log('); return; }
            if (e.key === 'f') { press('fact('); return; }
            if (e.key === 'r') { press('sqrt('); return; }
        });

        display.addEventListener('input', updateExprLine);
    </script>
</body>

</html>