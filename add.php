<?php
/**
 * add.php — модуль добавления новой записи.
 * Содержит форму и PHP-код обработки в одном файле.
 */

require_once __DIR__ . '/db.php';

$message = '';
$msgClass = '';

// Обработка отправки формы
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['button'])) {
    $surname  = trim($_POST['surname']  ?? '');
    $name     = trim($_POST['name']     ?? '');
    $lastname = trim($_POST['lastname'] ?? '');
    $gender   = trim($_POST['gender']   ?? '');
    $date     = trim($_POST['date']     ?? '');
    $phone    = trim($_POST['phone']    ?? '');
    $location = trim($_POST['location'] ?? '');
    $email    = trim($_POST['email']    ?? '');
    $comment  = trim($_POST['comment']  ?? '');

    if ($surname === '' || $name === '') {
        $message  = 'Ошибка: фамилия и имя обязательны для заполнения.';
        $msgClass = 'error';
    } else {
        try {
            $pdo  = getDB();
            $stmt = $pdo->prepare(
                'INSERT INTO contacts (surname, name, lastname, gender, date, phone, location, email, comment)
                 VALUES (:surname, :name, :lastname, :gender, :date, :phone, :location, :email, :comment)'
            );
            $stmt->execute([
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
            $message  = 'Запись добавлена';
            $msgClass = 'success';
        } catch (PDOException $e) {
            $message  = 'Ошибка: запись не добавлена';
            $msgClass = 'error';
        }
    }
}

$button = 'Добавить';
$row = [
    'surname'  => '',
    'name'     => '',
    'lastname' => '',
    'gender'   => '',
    'date'     => '',
    'phone'    => '',
    'location' => '',
    'email'    => '',
    'comment'  => '',
];
?>

<?php if ($message !== ''): ?>
    <p class="<?= $msgClass ?>"><?= htmlspecialchars($message) ?></p>
<?php endif; ?>

<form name="form_add" method="post" action="index.php?action=add">
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
                <option value="мужской"  <?= $row['gender'] === 'мужской'  ? 'selected' : '' ?>>мужской</option>
                <option value="женский"  <?= $row['gender'] === 'женский'  ? 'selected' : '' ?>>женский</option>
            </select>
        </div>
        <div class="add">
            <label>Дата рождения</label>
            <input type="date" name="date" value="<?= htmlspecialchars($row['date']) ?>">
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