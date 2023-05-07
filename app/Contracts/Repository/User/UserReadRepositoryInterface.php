<?php

declare(strict_types=1);

namespace App\Contracts\Repository\User;

use App\Contracts\Repository\Base\ReadRepositoryInterface;

interface UserReadRepositoryInterface extends ReadRepositoryInterface
{
    public function allStatuses(): array;
}
