<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8" />
  <title>Feedback Form — Страница 2</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>

<header>
  <img src="img/logo.jpg" alt="МосПолитех" />
  <h1>Самостоятельная работа «Feedback Form»</h1>
</header>

<main>
  <h2>Результат функции get_headers</h2>
  <p>URL: <strong>https://httpbin.org/post</strong></p>

  <?php
// Если форма была отправлена то показываем ответ
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Отправляем данные на httpbin.org
    $ch = curl_init('https://httpbin.org/post');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $_POST);
    $response = curl_exec($ch);
    curl_close($ch);
    
    $output = $response;
} else {
    // Если просто перешли на страницу то заголовки
    $url = 'https://httpbin.org/post';
    $headers = get_headers($url);
    $output = implode("\n", $headers);
}
?>

<textarea rows="15" readonly><?php echo htmlspecialchars($output); ?></textarea>

  <a href="index.php">← Вернуться на страницу 1</a>
</main>

<footer>
  <p>Задание: Самостоятельная работа «Feedback Form»</p>
</footer>

</body>
</html>
