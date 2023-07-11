<?php

declare(strict_types=1);

namespace App\Domain\Payments\Enums;

enum Provider: string
{
    case PLACE_TO_PAY = 'Place To Pay';

    public static function toArray(): array
    {
        return array_column(array: self::cases(), column_key: 'value');
    }
}
