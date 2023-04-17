<?php

declare(strict_types=1);

namespace App\Enums\User;

use BenSampo\Enum\Enum;

final class RoleType extends Enum
{
    const ADMINISTRATOR = 'role.administrator';

    const BUYER = 'role.buyer';
}
