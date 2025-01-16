<?php

namespace App\Models;

class Role {
    public const STUDENT = 1;
    public const TEACHER  = 2;
    public const ADMIN = 3;

    private const PERMISSIONS = [
        self::ADMIN => [
        ],
        self::TEACHER => [

        ],
        self::STUDENT => [

        ],
    ];

    public static function hasPermission(int $role, string $permission): bool {
        if (!isset(self::PERMISSIONS[$role])) {
            return false;
        }

        return in_array($permission, self::PERMISSIONS[$role]);
    }
    public static function getRoleLabel(int $role): string {
        return match($role) {
            self::ADMIN => 'Administrateur',
            self::TEACHER => 'Enseignant',
            self::STUDENT => 'Etudiant',
            default => 'Rôle inconnu'
        };
    }
    public static function getAllRoles(): array {
        return [
            self::ADMIN => self::getRoleLabel(self::ADMIN),
            self::TEACHER => self::getRoleLabel(self::TEACHER),
            self::STUDENT => self::getRoleLabel(self::STUDENT),
        ];
    }
}
