<?php session_start(); ?>
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
        <h1 class="h3 mb-4">Ajouter une todo</h1>

        <?php if (isset($_SESSION['alert'])) { ?>
            <div class="alert alert-<?= $_SESSION['alert']['type'] ?>" role="alert">
                <?= htmlspecialchars($_SESSION['alert']['message']) ?>
            </div>
        <?php } ?>

        <form action="./store.php" method="POST">
            <div class="mb-3">
                <label for="titre" class="form-label">Titre</label>
                <input type="text" class="form-control <?= isset($_SESSION['errors']['titre']) ? 'is-invalid' : '' ?>" id="titre" name="titre"
                    placeholder="Titre" value="<?= htmlspecialchars($_SESSION['old']['titre'] ?? '') ?>">
                <?php if (isset($_SESSION['errors']['titre'])) { ?>
                    <div class="invalid-feedback">
                        <?= htmlspecialchars($_SESSION['errors']['titre']) ?>
                    </div>
                <?php } ?>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input <?= isset($_SESSION['errors']['statut']) ? 'is-invalid' : '' ?>" id="statut" name="statut" value="1"
                    <?= isset($_SESSION['old']['statut']) ? 'checked' : '' ?>>
                <label class="form-check-label" for="statut">Terminé</label>
                <?php if (isset($_SESSION['errors']['statut'])) { ?>
                    <div class="invalid-feedback">
                        <?= htmlspecialchars($_SESSION['errors']['statut']) ?>
                    </div>
                <?php } ?>
            </div>
            <button type="submit" class="btn btn-primary">Enregistrer</button>
            <a href="./index.php" class="btn btn-link">Retour</a>
        </form>
    </div>

    <?php
    unset($_SESSION['alert'], $_SESSION['errors'], $_SESSION['old'])
    ?>
</body>

</html>
