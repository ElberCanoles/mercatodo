<?php

declare(strict_types=1);

namespace App\Enums\User;

use BenSampo\Enum\Enum;

final class UserStatus extends Enum
{
    public const ACTIVE = 'user.active';

    public const INACTIVE = 'user.inactive';
}
