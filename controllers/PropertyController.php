<?php

if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', realpath(dirname(__DIR__)) . DIRECTORY_SEPARATOR);
}
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

/**
 * Nettoie une chaîne numérique pour la convertir proprement en float
 * (Enlève les espaces et remplace la virgule par un point)
 */
function sanitizeNumber($value): float
{
    if (empty($value)) return 0.0;
    // Enlever les espaces (séparateurs de milliers fréquents) et normaliser la virgule
    $cleanValue = str_replace([' ', "\xc2\xa0", ','], ['', '', '.'], $value);
    return (float) $cleanValue;
}

function createProperty(): void
{
   
    global $propertyModel;

    // Vérification de sécurité : si $_POST est vide alors que le formulaire a été soumis
    // cela peut signifier que les fichiers sont trop lourds pour la configuration PHP.
    if (empty($_POST)) {
        flash('error', 'Les données n\'ont pas été reçues. Vérifiez que vos photos ne sont pas trop volumineuses.');
        redirect('views/property/create.php');
        return;
    }

    // Validation minimale des champs obligatoires
    if (empty(trim($_POST['titre'] ?? '')) || empty($_POST['prix'])) {
        flash('error', 'Le titre et le prix sont obligatoires.');
        redirect('views/property/create.php');
        return;
    }

    $data = [
        'owner_id' => $_SESSION['user_id'],
        'titre' => trim($_POST['titre'] ?? ''),
        'type' => $_POST['type'] ?? 'villa',
        'usage_type' => $_POST['usage_type'] ?? 'residence',
        'option_type' => $_POST['option_type'] ?? 'vente',
        'superficie' => sanitizeNumber($_POST['superficie'] ?? 0),
        'prix' => sanitizeNumber($_POST['prix'] ?? 0),
        'ville' => trim($_POST['ville'] ?? ''),
        'adresse' => trim($_POST['adresse'] ?? ''),
        'description' => trim($_POST['description'] ?? '')
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
        'titre' => trim($_POST['titre'] ?? ''),
        'type' => $_POST['type'] ?? $property['type'],
        'usage_type' => $_POST['usage_type'] ?? $property['usage_type'],
        'option_type' => $_POST['option_type'] ?? $property['option_type'],
        'superficie' => !empty($_POST['superficie']) ? sanitizeNumber($_POST['superficie']) : (float)$property['superficie'],
        'prix' => !empty($_POST['prix']) ? sanitizeNumber($_POST['prix']) : (float)$property['prix'],
        'ville' => trim($_POST['ville'] ?? ''),
        'adresse' => trim($_POST['adresse'] ?? ''),
        'description' => trim($_POST['description'] ?? ''),
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
