<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8" />
  <title>Hello, World!</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>

<header>
  <img src="https://mospolytech.ru/upload/iblock/c3c/logo.png" alt="МосПолитех" />
  <h1>Самостоятельная работа «Hello, World!»</h1>
</header>

<main>
  <?php
    $greeting = 'Hello, World!';
    $time     = date('H:i:s');
    $date     = date('d.m.Y');

    if (date('H') < 12) {
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
  <p>Задание: Самостоятельная работа «Hello, World!»</p>
  <p><a href="https://github.com/kirill-alex/php.git  ">github.com/kirill-alex/php.git  </a></p>
</footer>

</body>
</html>
