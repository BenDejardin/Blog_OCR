<?php

namespace Models;

class LoginModel extends DB{

    public function __construct()
    {
        parent::__construct();
        $this->table = 'users';
    }

    public function login(string $username): mixed
    {
        $query = $this->getPdo()->prepare("SELECT * FROM $this->table WHERE username = :username");
        $query->execute(['username' => $username]);
        $user = $query->fetch();
        return $user;
    }

    public function register(string $username, string $password, string $email): void
    {
        $query = $this->getPdo()->prepare("INSERT INTO $this->table (username, pwd, email) VALUES (:username, :pwd, :email)");
        $query->execute([
            'username' => $username,
            'pwd' => password_hash($password, PASSWORD_DEFAULT),
            'email' => $email
        ]);
    }
}