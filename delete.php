<?php
/**
 * delete.php — модуль удаления записи из базы данных.
 */

require_once __DIR__ . '/db.php';

$pdo = getDB();

$message  = '';
$msgClass = '';

// Обработка удаления
if (!empty($_GET['delete_id'])) {
    $deleteId = (int)$_GET['delete_id'];

    // Получаем фамилию перед удалением
    $stmt = $pdo->prepare('SELECT surname FROM contacts WHERE id = :id');
    $stmt->execute([':id' => $deleteId]);
    $victim = $stmt->fetchColumn();

    if ($victim !== false) {
        try {
            $stmt = $pdo->prepare('DELETE FROM contacts WHERE id = :id');
            $stmt->execute([':id' => $deleteId]);
            $message  = 'Запись с фамилией ' . htmlspecialchars($victim) . ' удалена';
            $msgClass = 'success';
        } catch (PDOException $e) {
            $message  = 'Ошибка: не удалось удалить запись';
            $msgClass = 'error';
        }
    } else {
        $message  = 'Ошибка: запись не найдена';
        $msgClass = 'error';
    }
}

// Получаем актуальный список контактов (после возможного удаления)
$contacts = $pdo->query(
    'SELECT id, surname, name, lastname FROM contacts ORDER BY surname ASC, name ASC'
)->fetchAll();
?>

<?php if ($message !== ''): ?>
    <p class="<?= $msgClass ?>"><?= $message ?></p>
<?php endif; ?>

<?php if (empty($contacts)): ?>
    <p>Записей нет.</p>
<?php else: ?>
    <div class="div-edit" style="margin: 20px auto; width: auto; display: inline-block; text-align: left;">
        <?php foreach ($contacts as $contact): ?>
            <?php
                // Инициалы
                $initial1 = mb_strtoupper(mb_substr($contact['name'],     0, 1)) . '.';
                $initial2 = !empty($contact['lastname'])
                    ? mb_strtoupper(mb_substr($contact['lastname'], 0, 1)) . '.'
                    : '';
                $label = htmlspecialchars($contact['surname']) . ' ' . $initial1 . $initial2;
            ?>
            <div>
                <a href="index.php?action=delete&delete_id=<?= (int)$contact['id'] ?>"
                   onclick="return confirm('Удалить запись «<?= htmlspecialchars($contact['surname']) ?>»?')">
                    <?= $label ?>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>