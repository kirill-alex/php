<?php
/**
 * viewer.php — модуль вывода содержимого базы данных.
 */

require_once __DIR__ . '/db.php';

/**
 * Возвращает HTML-строку с таблицей контактов и пагинацией.
 *
 * @param string $sort  Поле сортировки: 'id' | 'surname' | 'date'
 * @param int    $page  Номер страницы (начиная с 1)
 * @return string
 */
function getViewer(string $sort = 'id', int $page = 1): string
{
    $perPage = 10;

    $allowedSorts = ['id' => 'id', 'surname' => 'surname', 'date' => 'date'];
    $orderBy = $allowedSorts[$sort] ?? 'id';

    $pdo = getDB();

    // Общее количество записей
    $total = (int)$pdo->query('SELECT COUNT(*) FROM contacts')->fetchColumn();
    $totalPages = max(1, (int)ceil($total / $perPage));
    $page = max(1, min($page, $totalPages));
    $offset = ($page - 1) * $perPage;

    $stmt = $pdo->prepare(
        "SELECT * FROM contacts ORDER BY {$orderBy} ASC LIMIT :limit OFFSET :offset"
    );
    $stmt->bindValue(':limit',  $perPage, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset,  PDO::PARAM_INT);
    $stmt->execute();
    $rows = $stmt->fetchAll();

    if ($total === 0) {
        return '<p>База данных пуста. Добавьте первую запись.</p>';
    }

    $html  = '<table>';
    $html .= '<thead><tr>';
    foreach (['№', 'Фамилия', 'Имя', 'Отчество', 'Пол', 'Дата рождения', 'Телефон', 'Адрес', 'Email', 'Комментарий'] as $th) {
        $html .= '<th>' . $th . '</th>';
    }
    $html .= '</tr></thead><tbody>';

    foreach ($rows as $i => $row) {
        $html .= '<tr>';
        $html .= '<td>' . ($offset + $i + 1) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['surname'])  . '</td>';
        $html .= '<td>' . htmlspecialchars($row['name'])     . '</td>';
        $html .= '<td>' . htmlspecialchars($row['lastname']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['gender'])   . '</td>';
        $html .= '<td>' . htmlspecialchars($row['date'])     . '</td>';
        $html .= '<td>' . htmlspecialchars($row['phone'])    . '</td>';
        $html .= '<td>' . htmlspecialchars($row['location']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['email'])    . '</td>';
        $html .= '<td>' . htmlspecialchars($row['comment'])  . '</td>';
        $html .= '</tr>';
    }

    $html .= '</tbody></table>';

    // Пагинация
    if ($totalPages > 1) {
        $html .= '<div class="pagination">';
        for ($p = 1; $p <= $totalPages; $p++) {
            $cls   = ($p === $page) ? ' class="current-page"' : '';
            $html .= '<a href="index.php?action=view&sort=' . $sort . '&page=' . $p . '"' . $cls . '>' . $p . '</a>';
        }
        $html .= '</div>';
    }

    return $html;
}