<?php
define('ROOT_PATH', __DIR__ . DIRECTORY_SEPARATOR);
require_once ROOT_PATH . 'includes/bootstrap.php';

guestOnly();

include ROOT_PATH . 'views/auth/login.php';
