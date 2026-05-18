<?php

namespace MyProject\Controllers;

use MyProject\Models\Comments\Comment;
use MyProject\Services\Db;
use MyProject\View\View;

class CommentsController
{
    private View $view;
    private Db $db;

    public function __construct()
    {
        $this->view = new View(__DIR__ . '/../../../templates');
        $this->db = new Db();
    }

    public function add(int $articleId): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /php/www/articles/' . $articleId);
            exit;
        }

        $text = trim($_POST['text'] ?? '');

        if ($text === '') {
            header('Location: /php/www/articles/' . $articleId);
            exit;
        }

        // Используем author_id = 1 (admin) так как авторизации ещё нет
        $this->db->execute(
            'INSERT INTO `comments` (article_id, author_id, text) VALUES (:article_id, :author_id, :text);',
            [
                ':article_id' => $articleId,
                ':author_id'  => 1,
                ':text'       => $text,
            ]
        );

        $commentId = $this->db->getLastInsertId();

        header('Location: /php/www/articles/' . $articleId . '#comment' . $commentId);
        exit;
    }

    public function edit(int $commentId): void
    {
        $result = $this->db->query(
            'SELECT * FROM `comments` WHERE id = :id;',
            [':id' => $commentId],
            Comment::class
        );

        if ($result === []) {
            $this->view->renderHtml('errors/404.php', [], 404);
            return;
        }

        $comment = $result[0];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $text = trim($_POST['text'] ?? '');

            if ($text !== '') {
                $this->db->execute(
                    'UPDATE `comments` SET text = :text WHERE id = :id;',
                    [':text' => $text, ':id' => $commentId]
                );
            }

            header('Location: /php/www/articles/' . $comment->getArticleId() . '#comment' . $commentId);
            exit;
        }

        $this->view->renderHtml('comments/edit.php', [
            'comment' => $comment,
            'title'   => 'Редактирование комментария',
        ]);
    }
}
