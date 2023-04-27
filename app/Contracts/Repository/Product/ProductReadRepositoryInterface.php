<?php

declare(strict_types=1);

namespace App\Contracts\Repository\Product;

use App\Contracts\Repository\Base\ReadRepositoryInterface;

interface ProductReadRepositoryInterface extends ReadRepositoryInterface
{
    public function allStatuses();
}
