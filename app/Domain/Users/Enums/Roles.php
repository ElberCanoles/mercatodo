<?php

declare(strict_types=1);

namespace App\Domain\Users\Enums;

enum Roles: string
{
    case ADMINISTRATOR = 'role.administrator';
    case BUYER = 'role.buyer';

    public static function toArray(): array
    {
        return array_column(array: self::cases(), column_key: 'value');
    }
}
