<?php

declare(strict_types=1);

namespace App\Domain\Payments\Enums\PlaceToPay;

use BenSampo\Enum\Enum;

final class RejectedStatuses extends Enum
{
    public const REJECTED = 'REJECTED';

    public const PARTIAL_EXPIRED = 'PARTIAL_EXPIRED';

    public const FAILED = 'FAILED';
}
