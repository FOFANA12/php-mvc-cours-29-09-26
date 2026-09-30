<?php

/** @var array $todos */
$title = "List todos";

?>


<h1 class="h3 mb-4">Mes todos</h1>

<div class="d-flex justify-content-end">
    <a href="/todos/create" class="btn btn-primary mb-3">Ajouter une todo</a>
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
                    <a href="/todos/edit?id=<?= $todo['id'] ?>" class="btn btn-sm btn-warning">Modifier</a>
                    <form action="/todos/delete" method="POST" class="d-inline">
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