<?php

namespace App\Exceptions;

use Exception;

class DbException extends Exception {
    public function __construct($message = "erreur de base de données", $code = 0, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }

    public function errorMessage() {
        return "Erreur [{$this->code}]: {$this->message} dans {$this->file} (ligne {$this->line})";
    }
}