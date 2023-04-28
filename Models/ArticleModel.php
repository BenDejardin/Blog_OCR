<?php

namespace Models;

class ArticleModel extends DB{

    public function __construct()
    {
        parent::__construct();
        $this->table = 'blog_posts';
    }

    public function getArticles(): array
    {
        return $this->all($this->table);
    }

    public function getArticle(int $idArticle): mixed
    {
        return $this->find($this->table, $idArticle);
    }

    public function createArticle(string $title, string $subtitle, string $content): void
    {
        $query = $this->getPdo()->prepare("INSERT INTO $this->table (title, content, subtitle, author) VALUES (:title, :content, :subtitle, :author)");
        $query->execute([
            'title' => $title,
            'content' => $content,
            'subtitle' => $subtitle,
            'author' => $_SESSION['username']
        ]);
    }

    public function updateArticle(int $idArticle, string $title, string $subtitle, string $content): void
    {
        $query = $this->getPdo()->prepare("UPDATE $this->table SET title = :title, subtitle = :subtitle, content = :content WHERE id = :id");
        $query->execute([
            'title' => $title,
            'subtitle' => $subtitle,
            'content' => $content,
            'id' => $idArticle
        ]);
    }
}