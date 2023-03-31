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

    public function getArticle(int $id): mixed
    {
        return $this->find($this->table, $id);
    }
}