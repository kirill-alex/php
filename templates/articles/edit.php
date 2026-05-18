<?php include __DIR__ . '/../header.php'; ?>

<h1>Редактирование статьи</h1>

<form method="post">
    <p>
        <label>Заголовок:<br>
            <input type="text" name="name" value="<?= htmlspecialchars($article->getName()) ?>" style="width: 100%; padding: 5px;">
        </label>
    </p>
    <p>
        <label>Текст:<br>
            <textarea name="text" rows="10" style="width: 100%; padding: 5px;"><?= htmlspecialchars($article->getText()) ?></textarea>
        </label>
    </p>
    <p>
        <button type="submit">Сохранить</button>
        <a href="/php/www/articles/<?= $article->getId() ?>">Отмена</a>
    </p>
</form>

<?php include __DIR__ . '/../footer.php'; ?>
