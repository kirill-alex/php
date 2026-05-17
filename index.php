<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8" />
  <title>Hello, World!</title>
  <link rel="stylesheet" href="styles.css" />
</head>
<body>

<header>
  <img src="img/Logo.jpg" alt="МосПолитех" />
  <h1>Самостоятельная работа «Hello, World!»</h1>
</header>

<main>
  <?php
    date_default_timezone_set('Europe/Moscow');
    $greeting = 'Hello, World!'; // переменные
    $time     = date('H:i:s'); // время с сервера
    $date     = date('d.m.Y'); // дата с сервера

    if (date('H') < 12) { // H - возращает час
      $wish = 'Доброе утро!';
    } elseif (date('H') < 18) {
      $wish = 'Добрый день!';
    } else {
      $wish = 'Добрый вечер!';
    }
  ?>

  <h2><?php echo $greeting; ?></h2> 

  <p>Сегодня: <strong><?php echo $date; ?></strong></p>
  <p>Время на сервере: <strong><?php echo $time; ?></strong></p>
  <p><?php echo $wish; ?></p>
</main>

<footer>
  <p>Задание для самостоятельной работы</p>
  <p><a href="https://github.com/kirill-alex/php.git">Github</a></p>
  <p><a href="https://alexeev.wuaze.com/lab1/">Хостинг</a></p>
</footer>

</body>
</html>
