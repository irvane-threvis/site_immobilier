<?php

if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', realpath(dirname(__DIR__)) . DIRECTORY_SEPARATOR);
}
require_once ROOT_PATH . 'includes/bootstrap.php';

requireRole('manager');

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'assign':
        $clientId = (int) ($_POST['client_id'] ?? 0);
        $agentId = (int) ($_POST['agent_id'] ?? 0);

        $stmt = $pdo->prepare(
            "INSERT INTO client_agent (client_id, agent_id) VALUES (?, ?)"
        );
        $stmt->execute([$clientId, $agentId]);

        // Envoyer une notification à l'agent pour l'informer
        $clientStmt = $pdo->prepare("SELECT nom, prenom FROM users WHERE id = ?");
        $clientStmt->execute([$clientId]);
        $client = $clientStmt->fetch();

        if ($client) {
            $msg = "Le manager vous a affecté un nouveau client : " . htmlspecialchars($client['nom'] . ' ' . $client['prenom']);
            $notifStmt = $pdo->prepare("INSERT INTO notifications (user_id, message) VALUES (?, ?)");
            $notifStmt->execute([$agentId, $msg]);
        }

        flash('success', 'Client affecté à l\'agent.');
        redirect('views/manager/assignments.php');
        break;

    case 'deleteUser':
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ? AND role != 'manager'");
        $stmt->execute([(int) ($_GET['id'] ?? 0)]);
        flash('success', 'Utilisateur supprimé.');
        redirect('views/manager/users.php');
        break;

    default:
        redirect('views/manager/dashboard.php');
}
