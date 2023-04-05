<?php declare(strict_types=1);

namespace App\Enums\User;

use BenSampo\Enum\Enum;


final class UserStatus extends Enum
{
    const ACTIVE = 'user.active';
    const INACTIVE = 'user.inactive';
}
