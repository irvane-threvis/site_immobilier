<?php
define('ROOT_PATH', __DIR__ . DIRECTORY_SEPARATOR);
require_once ROOT_PATH . 'includes/bootstrap.php';
header('Location: ' . url('controllers/AuthController.php?action=logout'));
exit;
