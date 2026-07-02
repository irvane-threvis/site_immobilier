<?php

class Property
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Vérifie si un bailleur est autorisé à soumettre un nouveau bien.
     * Règle : Maximum 5 biens par bailleur.
     */
    public function canSubmit($ownerId)
    {
        // Vérifier si l'utilisateur actuel a déjà 5 biens
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM properties WHERE owner_id = ?");
        $stmt->execute([$ownerId]);
        if ((int)$stmt->fetchColumn() >= 5) return false;

        return true;
    }

    public function create($data)
    {
        $sql = "INSERT INTO properties
            (owner_id, titre, type, usage_type, option_type, superficie, prix, ville, adresse, description, status)
            VALUES
            (:owner_id, :titre, :type, :usage_type, :option_type, :superficie, :prix, :ville, :adresse, :description, 'pending')";

        $stmt = $this->pdo->prepare($sql);
        
        // On s'assure que l'exécution a bien fonctionné avant de retourner l'ID
        if ($stmt->execute($data)) {
            $id = $this->pdo->lastInsertId();
            // Si l'ID retourné est 0, l'insertion a échoué (souvent à cause de types de données)
            if ($id == 0) {
                error_log("Erreur PDO: lastInsertId est 0 après Property::create");
                return false;
            }
            return $id;
        }
        return false;
    }

    public function getAll()
    {
        $sql = "SELECT p.*, GROUP_CONCAT(pi.image_path) as image_paths 
                FROM properties p 
                LEFT JOIN property_images pi ON p.id = pi.property_id 
                GROUP BY p.id 
                ORDER BY p.id DESC";
        
        $items = $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        foreach ($items as &$item) {
            $item['images'] = $item['image_paths'] ? explode(',', $item['image_paths']) : [];
        }
        return $items;
    }

    public function getPending()
    {
        $sql = "SELECT p.*, GROUP_CONCAT(pi.image_path) as image_paths 
                FROM properties p 
                LEFT JOIN property_images pi ON p.id = pi.property_id 
                WHERE p.status = 'pending'
                GROUP BY p.id 
                ORDER BY p.id DESC";
        
        $items = $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        foreach ($items as &$item) {
            $item['images'] = $item['image_paths'] ? explode(',', $item['image_paths']) : [];
        }
        return $items;
    }

    public function getPublished($limit = null)
    {
        // On utilise GROUP_CONCAT pour récupérer toutes les images liées en une seule requête
        $sql = "SELECT p.*, GROUP_CONCAT(pi.image_path) as image_paths 
                FROM properties p 
                LEFT JOIN property_images pi ON p.id = pi.property_id 
                WHERE p.status = 'published' 
                GROUP BY p.id 
                ORDER BY p.id DESC";
        
        if ($limit) {
            $sql .= " LIMIT " . (int) $limit;
        }

        $items = $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        foreach ($items as &$item) {
            $item['images'] = $item['image_paths'] ? explode(',', $item['image_paths']) : [];
        }
        return $items;
    }

    public function countPublished()
    {
        return (int) $this->pdo->query(
            "SELECT COUNT(*) FROM properties WHERE status = 'published'"
        )->fetchColumn();
    }

    public function search($filters = [], $page = 1, $perPage = 9)
    {
        $where = ["status = 'published'"];
        $params = [];

        if (!empty($filters['q'])) {
            $where[] = "(titre LIKE :q OR ville LIKE :q OR description LIKE :q)";
            $params['q'] = '%' . $filters['q'] . '%';
        }
        if (!empty($filters['type'])) {
            $where[] = "type = :type";
            $params['type'] = $filters['type'];
        }
        if (!empty($filters['option_type'])) {
            $where[] = "option_type = :option_type";
            $params['option_type'] = $filters['option_type'];
        }
        if (!empty($filters['ville'])) {
            $where[] = "ville LIKE :ville";
            $params['ville'] = '%' . $filters['ville'] . '%';
        }
        if (!empty($filters['prix_min'])) {
            $where[] = "prix >= :prix_min";
            $params['prix_min'] = $filters['prix_min'];
        }
        if (!empty($filters['prix_max'])) {
            $where[] = "prix <= :prix_max";
            $params['prix_max'] = $filters['prix_max'];
        }

        $whereClause = implode(' AND ', $where);
        $offset = ((int) $page - 1) * $perPage;

        $countStmt = $this->pdo->prepare("SELECT COUNT(*) FROM properties WHERE $whereClause");
        $countStmt->execute($params);
        $total = (int) $countStmt->fetchColumn();

        $sql = "SELECT p.*, GROUP_CONCAT(pi.image_path) as image_paths 
                FROM properties p 
                LEFT JOIN property_images pi ON p.id = pi.property_id 
                WHERE $whereClause 
                GROUP BY p.id 
                ORDER BY p.id DESC 
                LIMIT $perPage OFFSET $offset";
                
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($items as &$item) {
            $item['images'] = $item['image_paths'] ? explode(',', $item['image_paths']) : [];
        }

        return [
            'items' => $items,
            'total' => $total,
            'page' => (int) $page,
            'perPage' => $perPage,
            'totalPages' => max(1, (int) ceil($total / $perPage))
        ];
    }

    public function getByOwner($ownerId)
    {
        $sql = "SELECT p.*, GROUP_CONCAT(pi.image_path) as image_paths 
                FROM properties p 
                LEFT JOIN property_images pi ON p.id = pi.property_id 
                WHERE p.owner_id = ?
                GROUP BY p.id 
                ORDER BY p.id DESC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$ownerId]);
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($items as &$item) {
            $item['images'] = $item['image_paths'] ? explode(',', $item['image_paths']) : [];
        }
        return $items;
    }

    public function getById($id)
    {
        $sql = "SELECT p.*, GROUP_CONCAT(pi.image_path) as image_paths 
                FROM properties p 
                LEFT JOIN property_images pi ON p.id = pi.property_id 
                WHERE p.id = ?
                GROUP BY p.id";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        $item = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($item) {
            // On s'assure que images est un tableau de chaînes (chemins)
            $item['images'] = !empty($item['image_paths']) ? explode(',', $item['image_paths']) : [];
        }
        return $item;
    }

    public function getImages($propertyId)
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM property_images WHERE property_id = ?"
        );
        $stmt->execute([$propertyId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update($id, $data)
    {
        $sql = "UPDATE properties SET
            titre = :titre, type = :type, usage_type = :usage_type,
            option_type = :option_type, superficie = :superficie, prix = :prix,
            ville = :ville, adresse = :adresse, description = :description
            WHERE id = :id AND owner_id = :owner_id";

        $stmt = $this->pdo->prepare($sql);
        $data['id'] = $id;
        return $stmt->execute($data);
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM properties WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function validateProperty($id)
    {
        $stmt = $this->pdo->prepare(
            "UPDATE properties SET status = 'published' WHERE id = ?"
        );
        return $stmt->execute([$id]);
    }

    public function refuseProperty($id)
    {
        $stmt = $this->pdo->prepare(
            "UPDATE properties SET status = 'refused' WHERE id = ?"
        );
        return $stmt->execute([$id]);
    }

    public function retireProperty($id)
    {
        $stmt = $this->pdo->prepare(
            "UPDATE properties SET status = 'retired' WHERE id = ?"
        );
        return $stmt->execute([$id]);
    }

    public function getPendingProperties()
    {
        $stmt = $this->pdo->query(
            "SELECT p.*, u.nom, u.prenom, u.email, u.telephone
             FROM properties p
             JOIN users u ON p.owner_id = u.id
             WHERE p.status = 'pending'
             ORDER BY p.id DESC"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
