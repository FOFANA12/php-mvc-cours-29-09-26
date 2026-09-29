<?php

session_start();

use App\Core\Database;

require_once __DIR__ . '/../vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ./index.php');
    exit;
}
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header('Location: ./index.php');
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

    header('Location: ./edit.php?id=' . $id);
    exit;
} else {
    $pdo = Database::connect();

    $stmt = $pdo->prepare("UPDATE todos SET titre = :titre, statut = :statut WHERE id = :id");
    $stmt->execute([
        'titre' => $titre,
        'statut' => (int) $statut,
        'id' => $id
    ]);

    unset($_SESSION['errors'], $_SESSION['old']);

    $_SESSION['alert'] = [
        'type' => 'success',
        'message' => 'Todo a été modifiée avec succès'
    ];
}

header('Location: ./index.php');
exit;
