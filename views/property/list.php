<?php
define('ROOT_PATH', dirname(__DIR__, 2) . DIRECTORY_SEPARATOR);
require_once ROOT_PATH . 'includes/bootstrap.php';
$query = isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING'] ? '?' . $_SERVER['QUERY_STRING'] : '';
redirect('biens.php' . $query);
