<?php
require_once 'db.php';
require_once 'menu.php';

$action = $_GET['action'] ?? 'view';
$sort   = $_GET['sort']   ?? 'id';
$page   = max(1, (int)($_GET['page'] ?? 1));

$allowedActions = ['view', 'add', 'edit', 'delete'];
if (!in_array($action, $allowedActions)) {
    $action = 'view';
}

$allowedSorts = ['id', 'surname', 'date'];
if (!in_array($sort, $allowedSorts)) {
    $sort = 'id';
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Записная книжка</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Lemonada:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <?= getMenu($action) ?>
</header>

<?php if ($action === 'view'): ?>
    <div class="submenu">
        <a href="index.php?action=view&sort=id"      class="<?= $sort === 'id'      ? 'select' : '' ?>">По порядку добавления</a>
        <a href="index.php?action=view&sort=surname" class="<?= $sort === 'surname' ? 'select' : '' ?>">По фамилии</a>
        <a href="index.php?action=view&sort=date"    class="<?= $sort === 'date'    ? 'select' : '' ?>">По дате рождения</a>
    </div>
<?php endif; ?>

<main>
<?php
switch ($action) {
    case 'view':
        require_once 'viewer.php';
        echo getViewer($sort, $page);
        break;

    case 'add':
        require_once 'add.php';
        break;

    case 'edit':
        require_once 'edit.php';
        break;

    case 'delete':
        require_once 'delete.php';
        break;
}
?>
</main>

<footer></footer>

</body>
</html>