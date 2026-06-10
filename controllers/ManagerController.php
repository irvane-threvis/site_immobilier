<?php

define('ROOT_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);
require_once ROOT_PATH . 'includes/bootstrap.php';

requireRole('manager');

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'assign':
        $stmt = $pdo->prepare(
            "INSERT INTO client_agent (client_id, agent_id) VALUES (?, ?)"
        );
        $stmt->execute([
            (int) ($_POST['client_id'] ?? 0),
            (int) ($_POST['agent_id'] ?? 0)
        ]);
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
