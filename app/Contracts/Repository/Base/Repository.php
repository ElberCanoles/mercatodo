<?php

namespace App\Contracts\Repository\Base;

use App\Domain\Shared\Traits\Utilities\CheckAttribute;
use App\Domain\Shared\Traits\Utilities\NormalizeData;

class Repository
{
    use NormalizeData;
    use CheckAttribute;
}
