<?php require 'back.php'; ?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Калькулятор</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@300;400;500&family=Space+Grotesk:wght@400;500;600&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --bg:       #0f0f11;
      --surface:  #1a1a1f;
      --border:   #2a2a32;
      --accent:   #7c6af7;
      --accent2:  #a78bfa;
      --text:     #e8e8f0;
      --muted:    #6b6b80;
      --digit-bg: #212128;
      --op-bg:    #2a2040;
      --fn-bg:    #1e2030;
      --eq-bg:    #7c6af7;
      --danger:   #ef4444;
      --radius:   12px;
    }

    body {
      background: var(--bg);
      color: var(--text);
      font-family: 'Space Grotesk', sans-serif;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2rem 1rem;
    }

    .calc-wrap {
      width: 100%;
      max-width: 420px;
    }

    .calc {
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: 24px;
      overflow: hidden;
      box-shadow: 0 32px 80px rgba(0,0,0,.6), 0 0 0 1px rgba(124,106,247,.08);
    }

    /* ── Display ── */
    .display-area {
      padding: 1.5rem 1.5rem 1rem;
      background: linear-gradient(160deg, #141418 0%, #0f0f13 100%);
      border-bottom: 1px solid var(--border);
      min-height: 120px;
      display: flex;
      flex-direction: column;
      justify-content: flex-end;
      gap: 6px;
    }

    #expr-line {
      font-family: 'JetBrains Mono', monospace;
      font-size: 13px;
      color: var(--muted);
      min-height: 18px;
      letter-spacing: .02em;
      word-break: break-all;
      text-align: right;
    }

    #display {
      font-family: 'JetBrains Mono', monospace;
      font-size: 32px;
      font-weight: 300;
      color: var(--text);
      text-align: right;
      background: none;
      border: none;
      outline: none;
      width: 100%;
      letter-spacing: -.01em;
      transition: color .15s;
      caret-color: var(--accent);
    }

    #display.error { color: var(--danger); }

    #result-display {
      font-family: 'JetBrains Mono', monospace;
      font-size: 15px;
      color: var(--accent2);
      text-align: right;
      background: none;
      border: none;
      outline: none;
      width: 100%;
      min-height: 20px;
    }

    /* ── Buttons ── */
    .buttons {
      display: grid;
      grid-template-columns: repeat(5, 1fr);
      gap: 1px;
      background: var(--border);
    }

    button {
      background: var(--digit-bg);
      border: none;
      color: var(--text);
      font-family: 'Space Grotesk', sans-serif;
      font-size: 14px;
      font-weight: 500;
      padding: 0;
      height: 56px;
      cursor: pointer;
      transition: background .1s, transform .08s, color .1s;
      position: relative;
      overflow: hidden;
      user-select: none;
    }

    button:active { transform: scale(0.93); }

    button::after {
      content: '';
      position: absolute;
      inset: 0;
      background: rgba(255,255,255,0);
      transition: background .15s;
    }
    button:hover::after { background: rgba(255,255,255,.04); }

    button.op      { background: var(--op-bg); color: var(--accent2); }
    button.fn      { background: var(--fn-bg); color: #94a3b8; font-size: 12px; }
    button.fn:hover::after { background: rgba(120,100,247,.15); }
    button.clear   { background: #2a1020; color: #f87171; }
    button.del     { background: #1e1e26; color: var(--muted); }
    button.equals  {
      background: var(--eq-bg);
      color: #fff;
      font-size: 20px;
      grid-column: span 2;
    }
    button.equals:hover::after { background: rgba(255,255,255,.12); }

    button.zero { grid-column: span 2; }

    /* ── Keyboard hint ── */
    .hint {
      text-align: center;
      font-size: 11px;
      color: var(--muted);
      padding: .6rem;
      background: var(--surface);
      border-top: 1px solid var(--border);
      letter-spacing: .04em;
    }

    /* ── Links ── */
    .links {
      display: flex;
      justify-content: center;
      gap: 1.5rem;
      margin-top: 1.2rem;
    }
    .links a {
      font-size: 12px;
      color: var(--muted);
      text-decoration: none;
      letter-spacing: .03em;
      transition: color .15s;
    }
    .links a:hover { color: var(--accent2); }
  </style>
</head>
<body>
<div class="calc-wrap">
  <div class="calc">

    <div class="display-area">
      <div id="expr-line"></div>
      <input type="text" id="display"
             placeholder="0"
             autocomplete="off" spellcheck="false"
             style="color: <?= $displayError ? '#ef4444' : '' ?>"/>
      <input type="text" id="result-display" readonly
             value="<?= $displayError ? htmlspecialchars($displayError) : ($displayResult !== null ? '= ' . htmlspecialchars($displayResult) : '') ?>"/>
    </div>

    <form id="calc-form" method="POST" action="" style="display:none">
      <input type="hidden" name="expression" id="expression-input"/>
    </form>

    <div class="buttons">
      <!-- Row 1: functions -->
      <button class="fn" onclick="press('sin(')">sin</button>
      <button class="fn" onclick="press('cos(')">cos</button>
      <button class="fn" onclick="press('tan(')">tan</button>
      <button class="fn" onclick="press('ln(')">ln</button>
      <button class="fn" onclick="press('log(')">log</button>

      <!-- Row 2: functions -->
      <button class="fn" onclick="press('sqrt(')">√x</button>
      <button class="fn" onclick="press('cbrt(')">∛x</button>
      <button class="fn" onclick="press('^')">xʸ</button>
      <button class="fn" onclick="press('fact(')">x!</button>
      <button class="fn" onclick="press('abs(')">|x|</button>

      <!-- Row 3: constants + clear + del -->
      <button class="fn" onclick="press('pi')">π</button>
      <button class="fn" onclick="press('e')">e</button>
      <button class="fn" onclick="press('(')">(</button>
      <button class="fn" onclick="press(')')">)</button>
      <button class="del" onclick="deleteLast()">⌫</button>

      <!-- Row 4: 7 8 9 / -->
      <button onclick="press('7')">7</button>
      <button onclick="press('8')">8</button>
      <button onclick="press('9')">9</button>
      <button class="op" onclick="press('/')">/</button>
      <button class="clear" onclick="clearDisplay()">C</button>

      <!-- Row 5: 4 5 6 * -->
      <button onclick="press('4')">4</button>
      <button onclick="press('5')">5</button>
      <button onclick="press('6')">6</button>
      <button class="op" onclick="press('*')">×</button>
      <button class="op" onclick="press('%')">%</button>

      <!-- Row 6: 1 2 3 - -->
      <button onclick="press('1')">1</button>
      <button onclick="press('2')">2</button>
      <button onclick="press('3')">3</button>
      <button class="op" onclick="press('-')">−</button>
      <button class="op" onclick="press('+')">+</button>

      <!-- Row 7: 0 . = -->
      <button class="zero" onclick="press('0')">0</button>
      <button onclick="press('.')">.</button>
      <button class="op" onclick="press('(-')">(-</button>
      <button class="equals" onclick="calculate()">=</button>
    </div>

    <div class="hint">Ввод с клавиатуры поддерживается</div>
  </div>

  <div class="links">
    <a href="https://github.com/kirill-alex/php.git">GitHub</a>
    <a href="https://alexeev.wuaze.com/lab4/">Хостинг</a>
  </div>
</div>

<script>
  const display      = document.getElementById('display');
  const resultDisp   = document.getElementById('result-display');
  const exprLine     = document.getElementById('expr-line');

  // Restore expression from GET after redirect
  <?php if ($displayExpr): ?>
  display.value = <?= json_encode($displayExpr) ?>;
  exprLine.textContent = <?= json_encode($displayExpr) ?>;
  <?php endif; ?>

  // Pretty-print expression above display
  function updateExprLine() {
    exprLine.textContent = display.value || '';
  }

  function press(char) {
    // If result is shown and user presses a digit/fn, start fresh
    const val = display.value;
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

  // ── Keyboard support ──
  document.addEventListener('keydown', e => {
    if (e.key >= '0' && e.key <= '9')   { press(e.key); return; }
    if (['+','-','*','/','(',')','.','^','%'].includes(e.key)) { e.preventDefault(); press(e.key); return; }
    if (e.key === 'Enter' || e.key === '=') { e.preventDefault(); calculate(); return; }
    if (e.key === 'Backspace')  { e.preventDefault(); deleteLast(); return; }
    if (e.key === 'Escape')     { clearDisplay(); return; }
    // Allow typing directly (letters for fn names, etc.)
    if (e.key === 'p') { press('pi'); return; }
    if (e.key === 'e' && !e.ctrlKey) { press('e'); return; }
    if (e.key === 's') { press('sqrt('); return; }
    if (e.key === 'l') { press('ln('); return; }
    if (e.key === 'L') { press('log('); return; }
    if (e.key === 'f') { press('fact('); return; }
  });

  // Make display editable directly
  display.addEventListener('input', updateExprLine);
</script>
</body>
</html>