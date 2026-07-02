<?php

if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', realpath(dirname(__DIR__)) . DIRECTORY_SEPARATOR);
}
require_once ROOT_PATH . 'includes/bootstrap.php';
require_once ROOT_PATH . 'models/User.php';

$userModel = new User($pdo);
$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
    case 'register':
        register(null);
        break;
    case 'registerClient':
        register('client');
        break;
    case 'registerBailleur':
        register('bailleur');
        break;
    case 'login':
        login();
        break;
    case 'logout':
        logout();
        break;
    default:
        redirect('connexion.php');
}

function register($forcedRole = null)
{
    global $userModel;

    $role = $forcedRole;
    if ($role === null) {
        $role = isset($_POST['role']) ? $_POST['role'] : '';
    }

    if (!in_array($role, array('client', 'bailleur'), true)) {
        flash('error', 'Choisissez Client ou Bailleur.');
        redirect('inscription.php');
    }

    $nom = trim(isset($_POST['nom']) ? $_POST['nom'] : '');
    $prenom = trim(isset($_POST['prenom']) ? $_POST['prenom'] : '');
    $email = trim(isset($_POST['email']) ? $_POST['email'] : '');
    $telephone = trim(isset($_POST['telephone']) ? $_POST['telephone'] : '');
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if (empty($nom) || empty($prenom) || empty($email) || empty($telephone) || empty($password)) {
        flash('error', 'Tous les champs sont obligatoires.');
        redirect('inscription.php?role=' . $role);
    }

    if ($userModel->findByEmail($email)) {
        flash('error', 'Cet email est déjà utilisé.');
        redirect('inscription.php?role=' . $role);
    }

    $hash = password_hash($password, PASSWORD_BCRYPT);
    $userModel->create(array(
        'nom' => $nom,
        'prenom' => $prenom,
        'email' => $email,
        'telephone' => $telephone,
        'password' => $hash,
        'role' => $role
    ));

    $user = $userModel->findByEmail($email);
    loginUser($user);

    if ($role === 'bailleur') {
        flash('success', 'Compte bailleur créé ! Ajoutez votre première annonce.');
    } else {
        flash('success', 'Compte client créé ! Vous pouvez demander des visites et ajouter des favoris.');
    }

    redirectByRole($user['role']);
}

function login()
{
    global $userModel;

    $email = trim(isset($_POST['email']) ? $_POST['email'] : '');
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    $user = $userModel->findByEmail($email);

    if (!$user || !password_verify($password, $user['password'])) {
        flash('error', 'Email ou mot de passe incorrect.');
        redirect('connexion.php');
    }

    if ($user['status'] === 'inactive') {
        flash('error', 'Votre compte est désactivé.');
        redirect('connexion.php');
    }

    loginUser($user);
    redirectByRole($user['role']);
}

function loginUser($user)
{
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['nom'] = $user['nom'];
    $_SESSION['prenom'] = $user['prenom'];
    $_SESSION['role'] = $user['role'];
}

function logout()
{
    session_destroy();
    redirect('index.php');
}
