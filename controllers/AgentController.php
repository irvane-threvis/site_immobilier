<?php

define('ROOT_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);
require_once ROOT_PATH . 'includes/bootstrap.php';
require_once ROOT_PATH . 'models/VisitRequest.php';

requireRole('agent');

$model = new VisitRequest($pdo);
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'approveVisit':
        $model->approve((int) ($_GET['id'] ?? 0));
        flash('success', 'Visite approuvée.');
        break;
    case 'rejectVisit':
        $model->reject((int) ($_GET['id'] ?? 0));
        flash('success', 'Visite refusée.');
        break;
}

redirect('views/agent/visits.php');
