<?php
// BACK-END: обработка POST-запроса

$result = null;
$error  = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['expression'])) {

    $expr = trim($_POST['expression']);

    // Валидация: только цифры, операторы, скобки, пробелы
    if (!preg_match('/^[0-9+\-*\/().\s]+$/', $expr)) {
        $error = 'Недопустимые символы в выражении';
    } elseif ($expr === '') {
        $error = 'Выражение пустое';
    } else {
        // Токенизация
        $tokens = tokenize($expr);
        if ($tokens === false) {
            $error = 'Ошибка токенизации';
        } else {
            try {
                $pos    = 0;
                $result = parseExpr($tokens, $pos);
                if ($pos !== count($tokens)) {
                    throw new Exception('Лишние символы в выражении');
                }
            } catch (Exception $e) {
                $error = $e->getMessage();
            }
        }
    }

    // Возвращаем результат через GET-редирект
    if ($error) {
        header('Location: ' . $_SERVER['PHP_SELF'] . '?error=' . urlencode($error));
    } else {
        header('Location: ' . $_SERVER['PHP_SELF'] . '?result=' . urlencode($result) . '&expr=' . urlencode($expr));
    }
    exit;
}

// ──────────────────────────────────────────────
// Рекурсивный парсер выражений (LL-грамматика)
// Expr   = Term   { ('+' | '-') Term }
// Term   = Factor { ('*' | '/') Factor }
// Factor = '(' Expr ')' | Number
// ──────────────────────────────────────────────

function tokenize(string $expr): array|false
{
    $tokens = [];
    $i = 0;
    $len = strlen($expr);
    while ($i < $len) {
        if ($expr[$i] === ' ') { $i++; continue; }
        if (in_array($expr[$i], ['+', '-', '*', '/', '(', ')'])) {
            $tokens[] = $expr[$i++];
            continue;
        }
        if (is_numeric($expr[$i]) || $expr[$i] === '.') {
            $num = '';
            while ($i < $len && (is_numeric($expr[$i]) || $expr[$i] === '.')) {
                $num .= $expr[$i++];
            }
            $tokens[] = (float)$num;
            continue;
        }
        return false; // неизвестный символ
    }
    return $tokens;
}

function parseExpr(array &$tokens, int &$pos): float
{
    $left = parseTerm($tokens, $pos);
    while ($pos < count($tokens) && in_array($tokens[$pos], ['+', '-'])) {
        $op = $tokens[$pos++];
        $right = parseTerm($tokens, $pos);
        $left = ($op === '+') ? add($left, $right) : subtract($left, $right);
    }
    return $left;
}

function parseTerm(array &$tokens, int &$pos): float
{
    $left = parseFactor($tokens, $pos);
    while ($pos < count($tokens) && in_array($tokens[$pos], ['*', '/'])) {
        $op = $tokens[$pos++];
        $right = parseFactor($tokens, $pos);
        $left = ($op === '*') ? multiply($left, $right) : divide($left, $right);
    }
    return $left;
}

function parseFactor(array &$tokens, int &$pos): float
{
    if ($pos >= count($tokens)) {
        throw new Exception('Неожиданный конец выражения');
    }
    if ($tokens[$pos] === '(') {
        $pos++; // пропускаем '('
        $val = parseExpr($tokens, $pos);
        if ($pos >= count($tokens) || $tokens[$pos] !== ')') {
            throw new Exception('Отсутствует закрывающая скобка');
        }
        $pos++; // пропускаем ')'
        return $val;
    }
    if (is_numeric($tokens[$pos])) {
        return (float)$tokens[$pos++];
    }
    throw new Exception('Ожидалось число или выражение в скобках');
}

// Пользовательские функции для операций
function add(float $a, float $b): float      { return $a + $b; }
function subtract(float $a, float $b): float { return $a - $b; }
function multiply(float $a, float $b): float { return $a * $b; }
function divide(float $a, float $b): float
{
    if ($b == 0) throw new Exception('Деление на ноль');
    return $a / $b;
}

// Читаем GET-параметры для отображения
$displayResult = isset($_GET['result']) ? htmlspecialchars($_GET['result']) : null;
$displayExpr   = isset($_GET['expr'])   ? htmlspecialchars($_GET['expr'])   : null;
$displayError  = isset($_GET['error'])  ? htmlspecialchars($_GET['error'])  : null;
?>
