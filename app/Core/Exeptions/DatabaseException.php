<?php

namespace App\Core\Exeptions;

use Exception;

class DatabaseException extends Exception
{
    protected $message = "Erreur de base de données";
}
