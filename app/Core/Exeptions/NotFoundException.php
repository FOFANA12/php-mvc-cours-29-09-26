<?php

namespace App\Core\Exeptions;

use Exception;

class NotFoundException extends Exception
{
    protected $message = "Ressource introuvable";
}
