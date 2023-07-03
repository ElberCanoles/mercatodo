<?php

declare(strict_types=1);

namespace App\Domain\Payments\Enums\PlaceToPay;

use BenSampo\Enum\Enum;

final class PendingStatuses extends Enum
{
    public const PENDING = 'PENDING';
}
