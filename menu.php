<?php
/**
 * menu.php — модуль главного меню сайта.
 */

function getMenu(string $activeAction = 'view'): string
{
    $items = [
        'view'   => 'Просмотр',
        'add'    => 'Добавление записи',
        'edit'   => 'Редактирование записи',
        'delete' => 'Удаление записи',
    ];

    $html = '';
    foreach ($items as $action => $label) {
        $cls  = ($action === $activeAction) ? ' select' : '';
        $html .= '<a href="index.php?action=' . $action . '" class="' . $cls . '">' . $label . '</a>';
    }
    return $html;
}