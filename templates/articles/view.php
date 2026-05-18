<?php include __DIR__ . '/../header.php'; ?>

<h1><?= htmlspecialchars($article->getName()) ?></h1>
<p><?= htmlspecialchars($article->getText()) ?></p>
<p><strong>Автор:</strong> <?= $author ? htmlspecialchars($author->getNickname()) : 'Неизвестен' ?></p>
<p><a href="/php/www/article/<?= $article->getId() ?>/edit">Редактировать статью</a></p>

<hr>

<h2>Комментарии</h2>

<?php if (empty($comments)): ?>
    <p>Комментариев пока нет.</p>
<?php else: ?>
    <?php foreach ($comments as $comment): ?>
        <div id="comment<?= $comment->getId() ?>" style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;">
            <p>
                <strong><?= isset($commentAuthors[$comment->getAuthorId()]) ? htmlspecialchars($commentAuthors[$comment->getAuthorId()]->getNickname()) : 'Неизвестен' ?></strong>
                <em><?= htmlspecialchars($comment->getCreatedAt()) ?></em>
            </p>
            <p><?= htmlspecialchars($comment->getText()) ?></p>
            <a href="/php/www/comments/<?= $comment->getId() ?>/edit">Редактировать</a>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<h2>Добавить комментарий</h2>
<form method="post" action="/php/www/articles/<?= $article->getId() ?>/comments">
    <p>
        <textarea name="text" rows="5" style="width: 100%; padding: 5px;" placeholder="Введите комментарий..."></textarea>
    </p>
    <p>
        <button type="submit">Отправить</button>
    </p>
</form>

<?php include __DIR__ . '/../footer.php'; ?>