<?php

declare(strict_types=1);

namespace App\Domain\Users\Enums;

use BenSampo\Enum\Enum;

final class RoleType extends Enum
{
    public const ADMINISTRATOR = 'role.administrator';

    public const BUYER = 'role.buyer';
}
