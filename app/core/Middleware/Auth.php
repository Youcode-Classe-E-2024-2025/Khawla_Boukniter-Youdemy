<?php

namespace App\Core\Middleware;

class Auth
{
    public static function checkRole($allowedRoles = [])
    {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = "Vous devez être connecté pour accéder à cette page";
            header('Location: ' . base_url('login'));
            exit;
        }

        if (!empty($allowedRoles) && !in_array($_SESSION['user_role'], $allowedRoles)) {
            $_SESSION['error'] = "Vous n'avez pas les permissions nécessaires";
            header('Location: ' . base_url('dashboard'));
            exit;
        }
    }
}
