<?php

session_start();

use App\Core\Database;

require_once __DIR__ . '/../vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ./index.php');
    exit;
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if ($id) {
    $pdo = Database::connect();

    $stmt = $pdo->prepare("DELETE FROM todos WHERE id = :id");
    $stmt->execute(['id' => $id]);

    $_SESSION['alert'] = $stmt->rowCount() > 0
        ? ['type' => 'success', 'message' => 'Todo supprimée avec succès']
        : ['type' => 'danger', 'message' => 'Todo introuvable'];
}

header('Location: ./index.php');
exit;