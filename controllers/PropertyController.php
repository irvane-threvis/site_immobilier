<?php

define('ROOT_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);
require_once ROOT_PATH . 'includes/bootstrap.php';
require_once ROOT_PATH . 'models/Property.php';

$propertyModel = new Property($pdo);
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'create':
        requireRole('bailleur');
        createProperty();
        break;
    case 'update':
        requireRole('bailleur');
        updateProperty();
        break;
    case 'delete':
        requireRole('bailleur');
        deleteProperty();
        break;
    case 'validate':
        requireRole('agent');
        validateProperty();
        break;
    case 'refuse':
        requireRole('agent');
        refuseProperty();
        break;
    case 'retire':
        requireRole('manager');
        retireProperty();
        break;
    default:
        redirect('index.php');
}

function createProperty(): void
{
    global $propertyModel;

    $data = [
        'owner_id' => $_SESSION['user_id'],
        'titre' => htmlspecialchars(trim($_POST['titre'] ?? '')),
        'type' => $_POST['type'] ?? 'villa',
        'usage_type' => $_POST['usage_type'] ?? 'residence',
        'option_type' => $_POST['option_type'] ?? 'vente',
        'superficie' => $_POST['superficie'] ?? 0,
        'prix' => $_POST['prix'] ?? 0,
        'ville' => htmlspecialchars(trim($_POST['ville'] ?? '')),
        'adresse' => htmlspecialchars(trim($_POST['adresse'] ?? '')),
        'description' => htmlspecialchars(trim($_POST['description'] ?? ''))
    ];

    $propertyId = $propertyModel->create($data);
    uploadImages($propertyId);

    flash('success', 'Annonce soumise pour validation.');
    redirect('views/bailleur/dashboard.php');
}

function updateProperty(): void
{
    global $propertyModel;

    $id = (int) ($_POST['id'] ?? 0);
    $property = $propertyModel->getById($id);

    if (!$property || (int) $property['owner_id'] !== (int) $_SESSION['user_id']) {
        flash('error', 'Annonce introuvable.');
        redirect('views/bailleur/dashboard.php');
    }

    $data = [
        'titre' => htmlspecialchars(trim($_POST['titre'] ?? '')),
        'type' => $_POST['type'] ?? $property['type'],
        'usage_type' => $_POST['usage_type'] ?? $property['usage_type'],
        'option_type' => $_POST['option_type'] ?? $property['option_type'],
        'superficie' => $_POST['superficie'] ?? $property['superficie'],
        'prix' => $_POST['prix'] ?? $property['prix'],
        'ville' => htmlspecialchars(trim($_POST['ville'] ?? '')),
        'adresse' => htmlspecialchars(trim($_POST['adresse'] ?? '')),
        'description' => htmlspecialchars(trim($_POST['description'] ?? '')),
        'owner_id' => $_SESSION['user_id']
    ];

    $propertyModel->update($id, $data);

    if (!empty($_FILES['photos']['name'][0])) {
        uploadImages($id);
    }

    flash('success', 'Annonce mise à jour.');
    redirect('views/bailleur/dashboard.php');
}

function uploadImages(int $propertyId): void
{
    global $pdo;

    $uploadDir = ROOT_PATH . 'uploads/properties/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $total = count($_FILES['photos']['name'] ?? []);

    for ($i = 0; $i < $total; $i++) {
        if (($_FILES['photos']['error'][$i] ?? 1) === UPLOAD_ERR_OK) {
            $name = time() . '_' . $i . '_' . basename($_FILES['photos']['name'][$i]);
            $target = $uploadDir . $name;

            if (move_uploaded_file($_FILES['photos']['tmp_name'][$i], $target)) {
                $stmt = $pdo->prepare(
                    "INSERT INTO property_images (property_id, image_path) VALUES (?, ?)"
                );
                $stmt->execute([$propertyId, $name]);
            }
        }
    }
}

function deleteProperty(): void
{
    global $propertyModel;

    $id = (int) ($_GET['id'] ?? 0);
    $property = $propertyModel->getById($id);

    if ($property && (int) $property['owner_id'] === (int) $_SESSION['user_id']) {
        $propertyModel->delete($id);
        flash('success', 'Annonce supprimée.');
    }

    redirect('views/bailleur/dashboard.php');
}

function validateProperty(): void
{
    global $propertyModel;
    $propertyModel->validateProperty((int) ($_GET['id'] ?? 0));
    flash('success', 'Annonce validée.');
    redirect('views/agent/validations.php');
}

function refuseProperty(): void
{
    global $propertyModel;
    $propertyModel->refuseProperty((int) ($_GET['id'] ?? 0));
    flash('success', 'Annonce refusée.');
    redirect('views/agent/validations.php');
}

function retireProperty(): void
{
    global $propertyModel;
    $propertyModel->retireProperty((int) ($_GET['id'] ?? 0));
    flash('success', 'Annonce retirée.');
    redirect('views/manager/properties.php');
}
