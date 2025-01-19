<?php

namespace App\Models;

class Role
{
    public const STUDENT = 1;
    public const TEACHER  = 2;
    public const ADMIN = 3;

    public static function getRoleLabel(int $role): string
    {
        return match ($role) {
            self::ADMIN => 'Administrateur',
            self::TEACHER => 'Enseignant',
            self::STUDENT => 'Etudiant',
            default => 'Rôle inconnu'
        };
    }
}
