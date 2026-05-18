<?php include __DIR__ . '/../header.php'; ?>

<h1><?= htmlspecialchars($article->getName()) ?></h1>
<p><?= htmlspecialchars($article->getText()) ?></p>
<p><strong>Автор:</strong> <?= $author ? htmlspecialchars($author->getNickname()) : 'Неизвестен' ?></p>

<?php include __DIR__ . '/../footer.php'; ?>
