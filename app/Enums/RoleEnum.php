<?php

namespace App\Enums;

enum RoleEnum: string
{
    case admin = 'admin';
    case operator = 'operator';

    public function description()
    {
        return match ($this) {
            self::admin => 'Admin',
            self::operator => 'Operator',
        };
    }
}
