<?php

define('ROOT_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);
require_once ROOT_PATH . 'includes/bootstrap.php';
require_once ROOT_PATH . 'models/Favorite.php';
require_once ROOT_PATH . 'models/VisitRequest.php';

requireLogin();
requireRole('client');

$favorite = new Favorite($pdo);
$visit = new VisitRequest($pdo);
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'favorite':
        addFavorite();
        break;
    case 'removeFavorite':
        removeFavorite();
        break;
    case 'visit':
        requestVisit();
        break;
    default:
        redirect('views/client/dashboard.php');
}

function addFavorite(): void
{
    global $favorite;

    $favorite->add($_SESSION['user_id'], (int) ($_GET['property'] ?? 0));
    flash('success', 'Ajouté aux favoris.');
    header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? url('views/property/list.php')));
    exit;
}

function removeFavorite(): void
{
    global $favorite;

    $favorite->remove($_SESSION['user_id'], (int) ($_GET['property'] ?? 0));
    flash('success', 'Retiré des favoris.');
    redirect('views/client/favorites.php');
}

function requestVisit(): void
{
    global $visit;

    $visit->create((int) ($_GET['property'] ?? 0), $_SESSION['user_id']);
    flash('success', 'Demande de visite envoyée.');
    redirect('views/client/visits.php');
}
