<?php

namespace App\Controllers;

use App\Core\Exeptions\NotFoundException;
use App\Core\Validator;
use App\Models\Todo;

class TodoController
{
    public function getAll(): void
    {
        $todos = Todo::getAll();

        ob_start();
        require __DIR__ . '/../Views/todos/list.php';
        $view = ob_get_clean();

        require __DIR__ . '/../Views/layouts/app.php';
    }
    

    public function getCreateForm(): void
    {
        ob_start();
        require __DIR__ . '/../Views/todos/create.php';
        $view = ob_get_clean();
        require __DIR__ . '/../Views/layouts/app.php';
    }

    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /todos/create');
            exit;
        }

        $validator = (new Validator($_POST))->required('titre')->min('titre', 5)->max('titre', 50)->required('statut');

        if ($validator->fails()) {
            $_SESSION['errors'] = $validator->getErrors();
            $_SESSION['old'] = $_POST;

            $_SESSION['alert'] = [
                'type' => 'danger',
                'message' => 'Erreur de validation'
            ];

            header('Location: /todos/create');
            exit;
        } else {
            $todoId = Todo::create($_POST);

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
            throw new NotFoundException("Todo #$id introuvable");
        }

        ob_start();
        require __DIR__ . '/../Views/todos/edit.php';
        $view = ob_get_clean();
        require __DIR__ . '/../Views/layouts/app.php';
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

        $validator = (new Validator($_POST))->required('titre')->min('titre', 5)->max('titre', 50)->required('statut');

        if ($validator->fails()) {
            $_SESSION['errors'] = $validator->getErrors();
            $_SESSION['old'] = $_POST;

            $_SESSION['alert'] = [
                'type' => 'danger',
                'message' => 'Erreur de validation'
            ];

            header('Location: /todos/edit?id=' . $id);
            exit;
        } else {
            Todo::update($_POST, $id);

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
