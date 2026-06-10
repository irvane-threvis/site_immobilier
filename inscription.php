<?php
define('ROOT_PATH', __DIR__ . DIRECTORY_SEPARATOR);
require_once ROOT_PATH . 'includes/bootstrap.php';

guestOnly();

$defaultRole = isset($_GET['role']) && $_GET['role'] === 'bailleur' ? 'bailleur' : 'client';

include ROOT_PATH . 'views/auth/register.php';
