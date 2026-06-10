<?php

class VisitRequest
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function create(
        $propertyId,
        $clientId,
        $agentId = null
    )
    {
        $stmt = $this->pdo->prepare(
        "INSERT INTO visit_requests
        (
            property_id,
            client_id,
            agent_id
        )
        VALUES
        (
            ?,
            ?,
            ?
        )"
        );

        return $stmt->execute([
            $propertyId,
            $clientId,
            $agentId
        ]);
    }

    public function getClientVisits($clientId)
    {
        $stmt = $this->pdo->prepare(
        "SELECT vr.*,
                p.titre,
                p.ville
         FROM visit_requests vr
         JOIN properties p
         ON vr.property_id=p.id
         WHERE vr.client_id=?
         ORDER BY vr.created_at DESC"
        );

        $stmt->execute([$clientId]);

        return $stmt->fetchAll(
            PDO::FETCH_ASSOC
        );
    }

    public function approve($id)
    {
        $stmt = $this->pdo->prepare(
        "UPDATE visit_requests
        SET status='approved'
        WHERE id=?"
        );

        return $stmt->execute([$id]);
    }

    public function reject($id)
    {
        $stmt = $this->pdo->prepare(
        "UPDATE visit_requests
        SET status='rejected'
        WHERE id=?"
        );

        return $stmt->execute([$id]);
    }

    public function getPendingVisits()
{
    $stmt = $this->pdo->query(
    "SELECT vr.*,
            p.titre,
            u.nom,
            u.prenom
     FROM visit_requests vr
     JOIN properties p
     ON vr.property_id=p.id
     JOIN users u
     ON vr.client_id=u.id
     WHERE vr.status='pending'
     ORDER BY vr.id DESC"
    );

    return $stmt->fetchAll(
        PDO::FETCH_ASSOC
    );
}
}