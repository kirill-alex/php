<?php

namespace MyProject\Controllers;

use MyProject\Models\Articles\Article;
use MyProject\Services\Db;
use MyProject\View\View;

class ArticleController
{
    private View $view;
    private Db $db;

    public function __construct()
    {
        $this->view = new View(__DIR__ . '/../../../templates');
        $this->db = new Db();
    }

    public function edit(int $articleId): void
    {
        $result = $this->db->query(
            'SELECT * FROM `articles` WHERE id = :id;',
            [':id' => $articleId],
            Article::class
        );

        if ($result === []) {
            $this->view->renderHtml('errors/404.php', [], 404);
            return;
        }

        $article = $result[0];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $text = $_POST['text'] ?? '';

            $this->db->execute(
                'UPDATE `articles` SET name = :name, text = :text WHERE id = :id;',
                [':name' => $name, ':text' => $text, ':id' => $articleId]
            );

            header('Location: /php/www/articles/' . $articleId);
            exit;
        }

        $this->view->renderHtml('articles/edit.php', [
            'article' => $article,
            'title'   => 'Редактирование статьи',
        ]);
    }
}
