<?php include __DIR__ . '/../header.php'; ?>

<h1>Редактирование комментария</h1>

<form method="post">
    <p>
        <label>Текст комментария:<br>
            <textarea name="text" rows="5" style="width: 100%; padding: 5px;"><?= htmlspecialchars($comment->getText()) ?></textarea>
        </label>
    </p>
    <p>
        <button type="submit">Сохранить</button>
        <a href="/php/www/articles/<?= $comment->getArticleId() ?>#comment<?= $comment->getId() ?>">Отмена</a>
    </p>
</form>

<?php include __DIR__ . '/../footer.php'; ?>
