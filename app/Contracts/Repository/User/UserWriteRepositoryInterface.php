<?php

declare(strict_types=1);

namespace App\Contracts\Repository\User;

use App\Contracts\Repository\Base\WriteRepositoryInterface;

interface UserWriteRepositoryInterface extends WriteRepositoryInterface
{
    public function updatePassword(array $data, int $id): bool;
}
