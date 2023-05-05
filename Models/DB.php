<?php

namespace Models;

use PDO;

class DB{
    protected static $instance = null;
    protected string $table;

    /**
     * DB constructor.
     */
    public function __construct()
    {
        // On ne créer qu'une seule instance de PDO / Singleton
        if(self::$instance === null){
            self::$instance = new PDO('mysql:host=localhost;dbname=blog;charset=utf8', 'root', '', [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
            ]);
        }
        return self::$instance;
    }

    /**
     * Renvoi l'instance de PDO
     * @return PDO
     */
    protected function getPdo(): PDO
    {
        return self::$instance;
    } 

    /**
     * Renvoi tous les éléments d'une table
     * @param string $table
     * @return array
     */
    public function all(string $table): array
    {
        $query = self::$instance->query("SELECT * FROM $table");
        return $query->fetchAll();
    }

    /**
     * Renvoi un élément d'une table
     * @param string $table
     * @param int $id
     * @return mixed
     */
    public function find(string $table, int $id): mixed
    {
        $query = $this->getPdo()->prepare("SELECT * FROM $table WHERE id = :id");
        $query->execute(['id' => $id]);
        return $query->fetch();
    }

    /**
     * Suppression d'un élément d'une table
     * @param int $id
     */
    public function delete(int $id): void
    {
        $query = $this->getPdo()->prepare("DELETE FROM $this->table WHERE id = :id");
        $query->execute(['id' => $id]);
    }
}