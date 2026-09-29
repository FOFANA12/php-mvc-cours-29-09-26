<?php

session_start();

use App\Core\Database;

require_once __DIR__ . '/../vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ./create.php');
    exit;
}

$errors = [];

$titre = trim(filter_input(INPUT_POST, 'titre')) ?? null;
$statut = isset($_POST['statut']);

if (is_null($titre)) {
    $errors['titre'] = "Le titre est obligatoire";
} else {
    if (strlen(trim($titre)) < 5) {
        $errors['titre'] = "Le titre doit avoir au moins 5 caractères";
    }

    if (strlen(trim($titre)) > 50) {
        $errors['titre'] = "Le titre ne pas être plus de 50 caractères";
    }
}

if (!$statut) {
    $errors['statut'] = "Le statut est obligatoire";
}

if (count($errors) > 0) {
    $_SESSION['errors'] = $errors;
    $_SESSION['old']['titre'] = $titre;
    if ($statut) {
        $_SESSION['old']['statut'] = $statut;
    }

    $_SESSION['alert'] = [
        'type' => 'danger',
        'message' => 'Erreur de validation'
    ];

    header('Location: ./create.php');
    exit;
} else {
    $pdo = Database::connect();

    $stmt = $pdo->prepare("INSERT INTO todos (titre, statut) VALUES (:titre, :statut)");
    $stmt->execute([
        'titre' => $titre,
        'statut' => (int) $statut
    ]);

    unset($_SESSION['errors'], $_SESSION['old']);

    $_SESSION['alert'] = [
        'type' => 'success',
        'message' => 'Todo a été enregistrée avec succès'
    ];
}

header('Location: ./index.php');
exit;
