<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo '<div style="background: #d4edda; border: 1px solid #c3e6cb; padding: 15px; margin-bottom: 20px; border-radius: 4px;">';
    echo '<strong>✓ Спасибо, ' . htmlspecialchars($_POST['name']) . '!</strong> Ваше обращение отправлено.';
    echo '</div>';
}
?><!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8" />
  <title>Feedback Form</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>

<header>
  <img src="img/logo.jpg" alt="МосПолитех" />
  <h1>Самостоятельная работа «Feedback Form»</h1>
</header>

<main>
  <form action="page2.php" method="POST">

    <label for="name">Имя пользователя</label>
    <input type="text" id="name" name="name" required />

    <label for="email">E-mail</label>
    <input type="email" id="email" name="email" required />

    <label for="type">Тип обращения</label>
    <select id="type" name="type">
      <option value="complaint">Жалоба</option>
      <option value="suggestion">Предложение</option>
      <option value="gratitude">Благодарность</option>
    </select>

    <label for="message">Текст обращения</label>
    <textarea id="message" name="message" rows="5" required></textarea>

    <label>Вариант ответа</label>
    <div class="checkboxes">
      <label><input type="checkbox" name="reply[]" value="sms" /> СМС</label>
      <label><input type="checkbox" name="reply[]" value="email" /> E-mail</label>
    </div>

    <button type="submit">Отправить</button>

  </form>

  <a href="page2.php">Перейти на страницу 2 →</a>
</main>

<footer>
  <p>Задание: Самостоятельная работа «Feedback Form»</p>
</footer>

</body>
</html>
