<?php

namespace MyProject\Controllers;

use MyProject\Models\Articles\Article;
use MyProject\Services\Db;
use MyProject\View\View;

class MainController
{
    private View $view;
    private Db $db;

    public function __construct()
    {
        $this->view = new View(__DIR__ . '/../../../templates');
        $this->db = new Db();
    }

    public function main(): void
    {
        $articles = $this->db->query('SELECT * FROM `articles`;', [], Article::class);
        $this->view->renderHtml('main/main.php', ['articles' => $articles]);
    }

    public function sayHello(string $name): void
    {
        $this->view->renderHtml('main/hello.php', ['name' => $name]);
    }

    public function sayBye(string $name): void
    {
        $this->view->renderHtml('main/bye.php', ['name' => $name]);
    }
}