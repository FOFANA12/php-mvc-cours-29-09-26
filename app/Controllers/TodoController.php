<?php

namespace App\Controllers;
use App\Models\Todo;

class TodoController {
    public function getAll(): void {
        $todos = Todo::getAll();
        require 'views/todos/list.php';
    }
}