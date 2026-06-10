<?php

define('ROOT_PATH', __DIR__ . DIRECTORY_SEPARATOR);
require_once ROOT_PATH . 'includes/bootstrap.php';
require_once ROOT_PATH . 'models/Property.php';

$propertyModel = new Property($pdo);
$latestProperties = $propertyModel->getPublished(12);

include ROOT_PATH . 'views/home/index.php';
