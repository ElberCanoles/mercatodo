<?php declare(strict_types=1);

namespace App\Enums\User;

use BenSampo\Enum\Enum;

final class UserVerify extends Enum
{
    const Verified = 'Verificado';
    const NonVerified = 'No Verificado';
}
