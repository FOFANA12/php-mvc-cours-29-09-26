<?php

use App\Controllers\TodoController;
use App\Core\Exeptions\DatabaseException;
use App\Core\Exeptions\NotFoundException;

session_start();

require_once __DIR__ . '/../vendor/autoload.php';

$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];
$uri = parse_url($uri, PHP_URL_PATH);
$uri = rtrim($uri, '/') ?: '/';

$controller = new TodoController();

try {
   if ($method === 'GET' && ($uri === '/' || $uri === '/todos')) {
      $controller->getAll();
   } elseif ($method === 'GET' && $uri === '/todos/create') {
      $controller->getCreateForm();
   } elseif ($method === 'POST' && $uri === '/todos/create') {
      $controller->store();
   } elseif ($method === 'GET' && $uri === '/todos/edit') {
      $controller->getEditForm();
   } elseif ($method === 'POST' && $uri === '/todos/update') {
      $controller->update();
   } elseif ($method === 'POST' && $uri === '/todos/delete') {
      $controller->destroy();
   } else {
     throw new NotFoundException("Route inconnue");
   }
} catch (NotFoundException $e) {
   http_response_code(404);
   require __DIR__ . '/../app/Views/errors/404.php';
} catch (DatabaseException $e) {
   echo "Error base de données";
} catch (Throwable $e) {
   http_response_code(500);
   echo "Une erreur est survenue";
}
