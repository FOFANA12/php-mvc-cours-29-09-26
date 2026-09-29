<?php
session_start();

use App\Core\Database;

require_once __DIR__ . '/../vendor/autoload.php';

$pdo = Database::connect();

$stmt = $pdo->prepare("SELECT * FROM todos");
$stmt->execute();
$todos = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projet Todo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container py-5">
        <h1 class="h3 mb-4">Mes todos</h1>

        <div class="d-flex justify-content-end">
            <a href="./create.php" class="btn btn-primary mb-3">Ajouter une todo</a>
        </div>

        <?php if (isset($_SESSION['alert'])) { ?>
            <div class="alert alert-<?= $_SESSION['alert']['type'] ?>" role="alert">
                <?= htmlspecialchars($_SESSION['alert']['message']) ?>
            </div>
        <?php } ?>

        <table class="table">
            <tr>
                <th>Titre</th>
                <th>Statut</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
            <?php if (count($todos) > 0) { ?>
                <?php foreach ($todos as $todo) { ?>
                    <tr>
                        <td><?= htmlspecialchars($todo['titre']) ?></td>
                        <td><?= $todo['statut'] ? 'Terminé' : 'En cours' ?></td>
                        <td><?= $todo['created_at'] ?></td>
                        <td>
                            <a href="./edit.php?id=<?= $todo['id'] ?>" class="btn btn-sm btn-warning">Modifier</a>
                            <form action="./delete.php" method="POST" class="d-inline">
                                <input type="hidden" name="id" value="<?= $todo['id'] ?>">
                                <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr>
                    <td colspan="4" class="text-center">Aucune todo pour le moment</td>
                </tr>
            <?php } ?>
        </table>
    </div>

    <?php
    unset($_SESSION['alert'])
    ?>
</body>

</html>