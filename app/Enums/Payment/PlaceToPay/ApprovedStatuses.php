<?php

declare(strict_types=1);

namespace App\Enums\Payment\PlaceToPay;

use BenSampo\Enum\Enum;

final class ApprovedStatuses extends Enum
{
    public const APPROVED = 'APPROVED';

    public const APPROVED_PARTIAL = 'APPROVED_PARTIAL';
}
