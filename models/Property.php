<?php

class Property
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function create($data)
    {
        $sql = "INSERT INTO properties
            (owner_id, titre, type, usage_type, option_type, superficie, prix, ville, adresse, description, status)
            VALUES
            (:owner_id, :titre, :type, :usage_type, :option_type, :superficie, :prix, :ville, :adresse, :description, 'pending')";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($data);
        return $this->pdo->lastInsertId();
    }

    public function getAll()
    {
        return $this->pdo->query(
            "SELECT * FROM properties ORDER BY id DESC"
        )->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPublished($limit = null)
    {
        $sql = "SELECT * FROM properties WHERE status = 'published' ORDER BY id DESC";
        if ($limit) {
            $sql .= " LIMIT " . (int) $limit;
        }
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
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

        $sql = "SELECT * FROM properties WHERE $whereClause ORDER BY id DESC LIMIT $perPage OFFSET $offset";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return [
            'items' => $stmt->fetchAll(PDO::FETCH_ASSOC),
            'total' => $total,
            'page' => (int) $page,
            'perPage' => $perPage,
            'totalPages' => max(1, (int) ceil($total / $perPage))
        ];
    }

    public function getPending()
    {
        return $this->pdo->query(
            "SELECT * FROM properties WHERE status = 'pending'"
        )->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByOwner($ownerId)
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM properties WHERE owner_id = ? ORDER BY id DESC"
        );
        $stmt->execute([$ownerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM properties WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
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
            "SELECT p.*, u.nom, u.prenom
             FROM properties p
             JOIN users u ON p.owner_id = u.id
             WHERE p.status = 'pending'
             ORDER BY p.id DESC"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
