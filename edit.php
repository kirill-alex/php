<?php
/**
 * edit.php — модуль редактирования существующей записи.
 * Содержит список контактов, форму редактирования и PHP-код обработки.
 */

require_once __DIR__ . '/db.php';

$pdo = getDB();

$message  = '';
$msgClass = '';

// Обработка отправки формы редактирования
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['button'])) {
    $id       = (int)($_POST['id'] ?? 0);
    $surname  = trim($_POST['surname']  ?? '');
    $name     = trim($_POST['name']     ?? '');
    $lastname = trim($_POST['lastname'] ?? '');
    $gender   = trim($_POST['gender']   ?? '');
    $date     = trim($_POST['date']     ?? '');
    $phone    = trim($_POST['phone']    ?? '');
    $location = trim($_POST['location'] ?? '');
    $email    = trim($_POST['email']    ?? '');
    $comment  = trim($_POST['comment']  ?? '');

    if ($id <= 0 || $surname === '' || $name === '') {
        $message  = 'Ошибка: не удалось сохранить изменения.';
        $msgClass = 'error';
    } else {
        try {
            $stmt = $pdo->prepare(
                'UPDATE contacts
                 SET surname=:surname, name=:name, lastname=:lastname, gender=:gender,
                     date=:date, phone=:phone, location=:location, email=:email, comment=:comment
                 WHERE id=:id'
            );
            $stmt->execute([
                ':id'       => $id,
                ':surname'  => $surname,
                ':name'     => $name,
                ':lastname' => $lastname,
                ':gender'   => $gender,
                ':date'     => $date ?: null,
                ':phone'    => $phone,
                ':location' => $location,
                ':email'    => $email,
                ':comment'  => $comment,
            ]);
            $message  = 'Запись сохранена';
            $msgClass = 'success';
        } catch (PDOException $e) {
            $message  = 'Ошибка: запись не сохранена';
            $msgClass = 'error';
        }
    }
}

// Получаем все контакты для списка (сортировка по фамилии, затем по имени)
$allContacts = $pdo->query(
    'SELECT id, surname, name FROM contacts ORDER BY surname ASC, name ASC'
)->fetchAll();

// Определяем текущую запись
// Приоритет: POST (после сохранения) → GET → первая запись
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['id'])) {
    $currentId = (int)$_POST['id'];
} elseif (!empty($_GET['id'])) {
    $currentId = (int)$_GET['id'];
} elseif (!empty($allContacts)) {
    $currentId = (int)$allContacts[0]['id'];
} else {
    $currentId = 0;
}

// Загружаем данные текущей записи
$row = null;
if ($currentId > 0) {
    $stmt = $pdo->prepare('SELECT * FROM contacts WHERE id = :id');
    $stmt->execute([':id' => $currentId]);
    $row = $stmt->fetch();
}

$button = 'Сохранить';
?>

<?php if (empty($allContacts)): ?>
    <p>База данных пуста. Сначала добавьте запись.</p>
<?php else: ?>

<div style="display:flex; gap:40px; justify-content:center; margin-top:20px;">

    <!-- Список контактов -->
    <div class="div-edit">
        <?php foreach ($allContacts as $contact): ?>
            <?php $isCurrent = ((int)$contact['id'] === $currentId); ?>
            <div class="<?= $isCurrent ? 'currentRow' : '' ?>">
                <a href="index.php?action=edit&id=<?= $contact['id'] ?>">
                    <?= htmlspecialchars($contact['surname'] . ' ' . $contact['name']) ?>
                </a>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Форма редактирования -->
    <div>
        <?php if ($message !== ''): ?>
            <p class="<?= $msgClass ?>"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <?php if ($row): ?>
        <form name="form_edit" method="post" action="index.php?action=edit">
            <input type="hidden" name="id" value="<?= (int)$row['id'] ?>">
            <div class="column">
                <div class="add">
                    <label>Фамилия</label>
                    <input type="text" name="surname" placeholder="Фамилия" value="<?= htmlspecialchars($row['surname']) ?>">
                </div>
                <div class="add">
                    <label>Имя</label>
                    <input type="text" name="name" placeholder="Имя" value="<?= htmlspecialchars($row['name']) ?>">
                </div>
                <div class="add">
                    <label>Отчество</label>
                    <input type="text" name="lastname" placeholder="Отчество" value="<?= htmlspecialchars($row['lastname']) ?>">
                </div>
                <div class="add">
                    <label>Пол</label>
                    <select name="gender">
                        <option value="">— выберите —</option>
                        <option value="мужской" <?= $row['gender'] === 'мужской' ? 'selected' : '' ?>>мужской</option>
                        <option value="женский" <?= $row['gender'] === 'женский' ? 'selected' : '' ?>>женский</option>
                    </select>
                </div>
                <div class="add">
                    <label>Дата рождения</label>
                    <input type="date" name="date" value="<?= htmlspecialchars($row['date'] ?? '') ?>">
                </div>
                <div class="add">
                    <label>Телефон</label>
                    <input type="text" name="phone" placeholder="Телефон" value="<?= htmlspecialchars($row['phone']) ?>">
                </div>
                <div class="add">
                    <label>Адрес</label>
                    <input type="text" name="location" placeholder="Адрес" value="<?= htmlspecialchars($row['location']) ?>">
                </div>
                <div class="add">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="Email" value="<?= htmlspecialchars($row['email']) ?>">
                </div>
                <div class="add">
                    <label>Комментарий</label>
                    <textarea name="comment" placeholder="Краткий комментарий"><?= htmlspecialchars($row['comment']) ?></textarea>
                </div>
                <button type="submit" name="button" value="<?= $button ?>" class="form-btn"><?= $button ?></button>
            </div>
        </form>
        <?php endif; ?>
    </div>

</div>
<?php endif; ?>