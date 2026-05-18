<?php

namespace MyProject\Controllers;

use MyProject\Models\Articles\Article;
use MyProject\Models\Users\User;
use MyProject\Services\Db;
use MyProject\View\View;

class ArticlesController
{
    private View $view;
    private Db $db;

    public function __construct()
    {
        $this->view = new View(__DIR__ . '/../../../templates');
        $this->db = new Db();
    }

    public function show(int $articleId): void
    {
        // Получаем статью по id
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

        // Получаем автора статьи по author_id
        $authorResult = $this->db->query(
            'SELECT * FROM `users` WHERE id = :id;',
            [':id' => $article->getAuthorId()],
            User::class
        );

        $author = $authorResult[0] ?? null;

        $this->view->renderHtml('articles/view.php', [
            'article' => $article,
            'author'  => $author,
        ]);
    }
}
