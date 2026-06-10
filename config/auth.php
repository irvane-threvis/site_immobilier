<?php

function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

function requireLogin()
{
    if (!isLoggedIn()) {
        redirect('connexion.php');
    }
}

function requireRole($role)
{
    requireLogin();
    if (($_SESSION['role'] ?? '') !== $role) {
        redirectByRole($_SESSION['role'] ?? '');
    }
}

function guestOnly()
{
    if (isLoggedIn()) {
        redirectByRole($_SESSION['role']);
    }
}

function currentUserId()
{
    return isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
}

function currentUserRole()
{
    return isset($_SESSION['role']) ? $_SESSION['role'] : null;
}

function getDashboardPath($role = null)
{
    $role = $role !== null ? $role : currentUserRole();

    switch ($role) {
        case 'client':
            return 'views/client/dashboard.php';
        case 'bailleur':
            return 'views/bailleur/dashboard.php';
        case 'agent':
            return 'views/agent/dashboard.php';
        case 'manager':
            return 'views/manager/dashboard.php';
        default:
            return 'index.php';
    }
}

function getRoleLabel($role = null)
{
    $role = $role !== null ? $role : currentUserRole();

    switch ($role) {
        case 'client':
            return 'Client';
        case 'bailleur':
            return 'Bailleur';
        case 'agent':
            return 'Agent';
        case 'manager':
            return 'Administrateur';
        default:
            return 'Utilisateur';
    }
}

function redirectByRole($role)
{
    $messages = array(
        'client'   => 'Bienvenue ! Accédez à vos favoris et demandes de visite.',
        'bailleur' => 'Bienvenue ! Gérez vos annonces immobilières.',
        'agent'    => 'Bienvenue ! Validez les annonces et gérez les visites.',
        'manager'  => 'Bienvenue ! Accédez à l\'administration.',
    );

    if (isset($messages[$role])) {
        flash('success', $messages[$role]);
    }

    redirect(getDashboardPath($role));
}
