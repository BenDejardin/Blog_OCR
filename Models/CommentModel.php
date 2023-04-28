<?php

namespace Models;

class CommentModel extends DB{

    public function __construct()
    {
        parent::__construct();
        $this->table = 'comments';
    }

    public function getComments(int $idComment): mixed
    {
        return $this->getPdo()->query("SELECT * FROM $this->table WHERE blog_post_id = $idComment AND is_validated = 1")->fetchAll();
    }

    public function getComment(int $idComment): mixed
    {
        return $this->find($this->table, $idComment);
    }

    public function getCommentsToValidate(): mixed
    {
        return $this->getPdo()->query("SELECT * FROM $this->table WHERE is_validated = 0  AND is_rejected = 0")->fetchAll();
    }

    public function createComment(int $idArticle, string $author, string $content): void
    {
        $query = $this->getPdo()->prepare("INSERT INTO $this->table (blog_post_id, author, content) VALUES (:id_article, :author, :content)");
        $query->execute([
            'id_article' => $idArticle,
            'author' => $author,
            'content' => $content
        ]);
    }

    public function deleteComment(int $idComment): void
    {
        $this->delete($idComment);
    }

    public function rejectComment(int $idComment): void
    {
        $query = $this->getPdo()->prepare("UPDATE $this->table SET is_rejected = 1 WHERE id = :id");
        $query->execute(['id' => $idComment]);
    }

    public function validateComment(int $idComment): void
    {
        $query = $this->getPdo()->prepare("UPDATE $this->table SET is_validated = 1 WHERE id = :id");
        $query->execute(['id' => $idComment]);
    }

    public function modifyComment(int $idComment, string $content): void
    {
        $query = $this->getPdo()->prepare("UPDATE $this->table SET content = :content, is_validated = :is_validated, date = :date WHERE id = :id");
        $query->execute([
            'id' => $idComment,
            'content' => $content,
            'is_validated' => 0,
            'date' => date('Y-m-d H:i:s')
        ]);
    }
}