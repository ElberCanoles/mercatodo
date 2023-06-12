<?php

declare(strict_types=1);

namespace App\Enums\User;

use BenSampo\Enum\Enum;

final class UserVerify extends Enum
{
    public const VERIFIED = 'user.verified';

    public const NON_VERIFIED = 'user.non_verified';
}
