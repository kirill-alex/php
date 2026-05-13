<?php
// ──────────────────────────────────────────────────────────────────
// back.php — бэкенд калькулятора
// ──────────────────────────────────────────────────────────────────

require_once __DIR__ . '/trig.php';

// Читаем выражение из файла Task/expression.txt
$exprFilePath = __DIR__ . '/Task/expression.txt';
$fileExpression = file_exists($exprFilePath) ? trim(file_get_contents($exprFilePath)) : null;

// ── Обработка POST ────────────────────────────────────────────────

$result = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['expression'])) {

    $expr = trim($_POST['expression']);

    if ($expr === '') {
        $error = 'Выражение пустое';
    } else {
        $tokens = tokenize($expr);
        if ($tokens === false) {
            $error = 'Недопустимые символы в выражении';
        } else {
            try {
                $pos = 0;
                $result = parseExpr($tokens, $pos);
                if ($pos !== count($tokens)) {
                    throw new Exception('Лишние символы в выражении');
                }
                // Форматирование результата
                if (is_float($result) && $result == (int) $result && abs($result) < 1e15) {
                    $result = (int) $result;
                } elseif (is_float($result)) {
                    $result = rtrim(rtrim(number_format($result, 10, '.', ''), '0'), '.');
                }
            } catch (Exception $e) {
                $error = $e->getMessage();
            }
        }
    }

    if ($error) {
        header('Location: ' . $_SERVER['PHP_SELF'] . '?error=' . urlencode($error));
    } else {
        header('Location: ' . $_SERVER['PHP_SELF'] . '?result=' . urlencode($result) . '&expr=' . urlencode($expr));
    }
    exit;
}

// ── Токенизатор ───────────────────────────────────────────────────

function tokenize(string $expr): array|false
{
    $tokens = [];
    $i = 0;
    $len = strlen($expr);

    while ($i < $len) {
        if ($expr[$i] === ' ') {
            $i++;
            continue;
        }

        // Числа
        if (ctype_digit($expr[$i]) || $expr[$i] === '.') {
            $num = '';
            while ($i < $len && (ctype_digit($expr[$i]) || $expr[$i] === '.')) {
                $num .= $expr[$i++];
            }
            $tokens[] = (float) $num;
            continue;
        }

        // Идентификаторы: функции и константы
        if (ctype_alpha($expr[$i]) || $expr[$i] === '_') {
            $word = '';
            while ($i < $len && (ctype_alnum($expr[$i]) || $expr[$i] === '_')) {
                $word .= $expr[$i++];
            }
            $known = ['sin', 'cos', 'tan', 'asin', 'acos', 'atan', 'ln', 'log', 'sqrt', 'cbrt', 'abs', 'fact', 'pi', 'e'];
            if (!in_array($word, $known))
                return false;
            $tokens[] = $word;
            continue;
        }

        // Операторы и скобки
        if (in_array($expr[$i], ['+', '-', '*', '/', '(', ')', '^', '%'])) {
            $tokens[] = $expr[$i++];
            continue;
        }

        return false;
    }

    return $tokens;
}

// ── Рекурсивный парсер (LL-грамматика) ───────────────────────────
//
// Expr   = Term   { ('+' | '-') Term }
// Term   = Power  { ('*' | '/' | '%') Power }
// Power  = Unary  { '^' Unary }
// Unary  = '-' Unary | Factor
// Factor = Function '(' Expr ')' | Constant | Number | '(' Expr ')'

function parseExpr(array &$t, int &$p): float
{
    $left = parseTerm($t, $p);
    while ($p < count($t) && in_array($t[$p], ['+', '-'])) {
        $op = $t[$p++];
        $right = parseTerm($t, $p);
        $left = $op === '+' ? add($left, $right) : subtract($left, $right);
    }
    return $left;
}

function parseTerm(array &$t, int &$p): float
{
    $left = parsePower($t, $p);
    while ($p < count($t) && in_array($t[$p], ['*', '/', '%'])) {
        $op = $t[$p++];
        $right = parsePower($t, $p);
        $left = match ($op) {
            '*' => multiply($left, $right),
            '/' => divide($left, $right),
            '%' => modulo($left, $right),
        };
    }
    return $left;
}

function parsePower(array &$t, int &$p): float
{
    $base = parseUnary($t, $p);
    if ($p < count($t) && $t[$p] === '^') {
        $p++;
        return power($base, parseUnary($t, $p));
    }
    return $base;
}

function parseUnary(array &$t, int &$p): float
{
    if ($p < count($t) && $t[$p] === '-') {
        $p++;
        return -parseUnary($t, $p);
    }
    if ($p < count($t) && $t[$p] === '+') {
        $p++;
        return parseUnary($t, $p);
    }
    return parseFactor($t, $p);
}

function parseFactor(array &$t, int &$p): float
{
    if ($p >= count($t))
        throw new Exception('Неожиданный конец выражения');

    $tok = $t[$p];

    // Все поддерживаемые функции
    $trigFuncs = ['sin', 'cos', 'tan', 'asin', 'acos', 'atan'];
    $otherFuncs = ['ln', 'log', 'sqrt', 'cbrt', 'abs', 'fact'];
    $allFuncs = array_merge($trigFuncs, $otherFuncs);

    if (in_array($tok, $allFuncs)) {
        $p++;
        if ($p >= count($t) || $t[$p] !== '(')
            throw new Exception("После $tok ожидается «(»");
        $p++;
        $arg = parseExpr($t, $p);
        if ($p >= count($t) || $t[$p] !== ')')
            throw new Exception('Отсутствует закрывающая скобка');
        $p++;

        // Тригонометрия — через callTrig() из trig.php (там символическая ссылка)
        if (in_array($tok, $trigFuncs)) {
            return callTrig($tok, $arg);
        }

        // Остальные функции — через символическую ссылку: calc + Sin/Cos/...
        return call_user_func('calc' . ucfirst($tok), $arg);
    }

    if ($tok === 'pi') {
        $p++;
        return M_PI;
    }
    if ($tok === 'e') {
        $p++;
        return M_E;
    }

    if (is_numeric($tok)) {
        $p++;
        return (float) $tok;
    }

    if ($tok === '(') {
        $p++;
        $val = parseExpr($t, $p);
        if ($p >= count($t) || $t[$p] !== ')')
            throw new Exception('Отсутствует закрывающая скобка');
        $p++;
        return $val;
    }

    throw new Exception("Неожиданный токен: $tok");
}

// ── Арифметические функции ────────────────────────────────────────

function add(float $a, float $b): float
{
    return $a + $b;
}
function subtract(float $a, float $b): float
{
    return $a - $b;
}
function multiply(float $a, float $b): float
{
    return $a * $b;
}
function divide(float $a, float $b): float
{
    if ($b == 0)
        throw new Exception('Деление на ноль');
    return $a / $b;
}
function modulo(float $a, float $b): float
{
    if ($b == 0)
        throw new Exception('Деление на ноль (остаток)');
    return fmod($a, $b);
}
function power(float $base, float $exp): float
{
    if ($base < 0 && floor($exp) != $exp)
        throw new Exception('Отрицательное основание с дробным показателем');
    return pow($base, $exp);
}

// ── Математические функции (вызываются через call_user_func) ──────

function calcLn(float $x): float
{
    if ($x <= 0)
        throw new Exception('ln определён только для положительных чисел');
    return log($x);
}
function calcLog(float $x): float
{
    if ($x <= 0)
        throw new Exception('log определён только для положительных чисел');
    return log10($x);
}
function calcSqrt(float $x): float
{
    if ($x < 0)
        throw new Exception('Корень из отрицательного числа');
    return sqrt($x);
}
function calcCbrt(float $x): float
{
    return $x >= 0 ? pow($x, 1 / 3) : -pow(-$x, 1 / 3);
}
function calcAbs(float $x): float
{
    return abs($x);
}
function calcFact(float $x): float
{
    if ($x < 0 || floor($x) != $x)
        throw new Exception('Факториал определён только для целых неотрицательных чисел');
    if ($x > 170)
        throw new Exception('Число слишком велико для факториала');
    $r = 1.0;
    for ($i = 2; $i <= (int) $x; $i++)
        $r *= $i;
    return $r;
}

// ── GET-параметры для фронтенда ───────────────────────────────────

$displayResult = isset($_GET['result']) ? htmlspecialchars($_GET['result']) : null;
$displayExpr = isset($_GET['expr']) ? htmlspecialchars($_GET['expr']) : null;
$displayError = isset($_GET['error']) ? htmlspecialchars($_GET['error']) : null;
?>