<?php

class Favorite
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function add($clientId, $propertyId)
    {
        $stmt = $this->pdo->prepare(
            "INSERT IGNORE INTO favorites (client_id, property_id) VALUES (?, ?)"
        );
        return $stmt->execute([$clientId, $propertyId]);
    }

    public function remove($clientId, $propertyId)
    {
        $stmt = $this->pdo->prepare(
            "DELETE FROM favorites WHERE client_id = ? AND property_id = ?"
        );
        return $stmt->execute([$clientId, $propertyId]);
    }

    public function getFavorites($clientId)
    {
        $stmt = $this->pdo->prepare(
            "SELECT p.* FROM favorites f
             JOIN properties p ON f.property_id = p.id
             WHERE f.client_id = ?"
        );
        $stmt->execute([$clientId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function isFavorite($clientId, $propertyId)
    {
        $stmt = $this->pdo->prepare(
            "SELECT id FROM favorites WHERE client_id = ? AND property_id = ?"
        );
        $stmt->execute([$clientId, $propertyId]);
        return (bool) $stmt->fetch();
    }
}
