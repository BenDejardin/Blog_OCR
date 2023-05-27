<?php

namespace Models;

class CommentModel extends DB{

    /**
     * CommentModel constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->table = 'comments';
    }

    /**
     * Renvoi tous les commentaires d'un article
     * @param int $idArticle
     * @return mixed
     */
    public function getComments(int $idArticle): mixed
    {
        $statement = $this->getPdo()->query("SELECT * FROM $this->table WHERE blog_post_id = $idArticle AND is_validated = 1");
        if ($statement) {
            return $statement->fetchAll();
        }
        return [];
    }

    /**
     * Renvoi un commentaire
     * @param int $idComment
     * @return mixed
     */
    public function getComment(int $idComment): mixed
    {
        return $this->find($this->table, $idComment);
    }

    /**
     * Renvoi tous les commentaires à valider
     * @return mixed
     */
    public function getCommentsToValidate(): mixed
    {
        $statement = $this->getPdo()->query("SELECT * FROM $this->table WHERE is_validated = 0 AND is_rejected = 0");
        if ($statement) {
            return $statement->fetchAll();
        }
        return [];
    }

    /**
     * Créer un commentaire
     * @param int $idArticle
     * @param string $author
     * @param string $content
     * @return void
     */
    public function createComment(int $idArticle, string $author, string $content): void
    {
        $query = $this->getPdo()->prepare("INSERT INTO $this->table (blog_post_id, author, content) VALUES (:id_article, :author, :content)");
        $query->execute([
            'id_article' => $idArticle,
            'author' => $author,
            'content' => $content
        ]);
    }

    /**
     * Suppression d'un commentaire
     * @param int $idComment
     */
    public function deleteComment(int $idComment): void
    {
        $this->delete($idComment);
    }

    /**
     * Suppression de tous les commentaires d'un article
     * @param int $idComment
     */
    public function rejectComment(int $idComment): void
    {
        $query = $this->getPdo()->prepare("UPDATE $this->table SET is_rejected = 1 WHERE id = :id");
        $query->execute(['id' => $idComment]);
    }

    /**
     * Validation d'un commentaire
     * @param int $idComment
     */
    public function validateComment(int $idComment): void
    {
        $query = $this->getPdo()->prepare("UPDATE $this->table SET is_validated = 1 WHERE id = :id");
        $query->execute(['id' => $idComment]);
    }

    /**
     * Modification d'un commentaire
     * @param int $idComment
     * @param string $content
     */
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