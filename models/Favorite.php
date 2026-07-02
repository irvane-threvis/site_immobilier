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
        $sql = "SELECT p.*, GROUP_CONCAT(pi.image_path) as image_paths 
                FROM favorites f
                JOIN properties p ON f.property_id = p.id
                LEFT JOIN property_images pi ON p.id = pi.property_id
                WHERE f.client_id = ?
                GROUP BY p.id";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$clientId]);
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($items as &$item) {
            $item['images'] = !empty($item['image_paths']) ? explode(',', $item['image_paths']) : [];
        }
        return $items;
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
