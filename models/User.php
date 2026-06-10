<?php

class User
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function create($data)
    {
        $sql = "
        INSERT INTO users
        (
            nom,
            prenom,
            email,
            telephone,
            password,
            role
        )
        VALUES
        (
            :nom,
            :prenom,
            :email,
            :telephone,
            :password,
            :role
        )";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            'nom' => $data['nom'],
            'prenom' => $data['prenom'],
            'email' => $data['email'],
            'telephone' => $data['telephone'],
            'password' => $data['password'],
            'role' => $data['role']
        ]);
    }

    public function findByEmail($email)
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM users WHERE email=?"
        );

        $stmt->execute([$email]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findById($id)
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM users WHERE id=?"
        );

        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}