<?php

namespace App\Controllers;

use App\Models\Todo;

class TodoController
{
    public function getAll(): void
    {
        $todos = Todo::getAll();
        require __DIR__ . '/../Views/todos/list.php';
    }

    public function getCreateForm(): void
    {
        require __DIR__ . '/../Views/todos/create.php';
    }

    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /todos/create');
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

            header('Location: /todos/create');
            exit;
        } else {
            $todoId = Todo::create([
                'titre' => $titre,
                'statut' => (int) $statut
            ]);

            if ($todoId) {
                unset($_SESSION['errors'], $_SESSION['old']);

                $_SESSION['alert'] = [
                    'type' => 'success',
                    'message' => 'Todo a été enregistrée avec succès'
                ];
                header('Location: /todos');
                exit;
            } else {

                $_SESSION['alert'] = [
                    'type' => 'danger',
                    'message' => "Erreur d'insertion dans la base"
                ];

                header('Location: /todos/create');
                exit;
            }
        }
    }

    public function getEditForm(): void
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $todo = Todo::findById($id);
        if (!$todo) {
            header('Location: /todos');
            exit;
        }
        require __DIR__ . '/../Views/todos/edit.php';
    }

    public function update(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /todos');
            exit;
        }

        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

        if (!$id) {
            header('Location: /todos');
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

            header('Location: /todos/edit?id=' . $id);
            exit;
        } else {
            Todo::update(
                [
                    'titre' => $titre,
                    'statut' => (int) $statut,
                ],
                $id
            );

            unset($_SESSION['errors'], $_SESSION['old']);

            $_SESSION['alert'] = [
                'type' => 'success',
                'message' => 'Todo a été modifiée avec succès'
            ];
            header('Location: /todos');
            exit;
        }
    }

    public function destroy(): void
    {

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /todos');
            exit;
        }

        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

        if ($id) {
            $rowCount = Todo::destroy($id);

            $_SESSION['alert'] = $rowCount > 0
                ? ['type' => 'success', 'message' => 'Todo supprimée avec succès']
                : ['type' => 'danger', 'message' => 'Todo introuvable'];
        }

        header('Location: /todos');
        exit;
    }
}
