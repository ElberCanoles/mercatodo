<?php

namespace App\Contracts\Repository\Base;

use App\Traits\Utilities\CheckAttribute;
use App\Traits\Utilities\NormalizeData;

class Repository
{
    use NormalizeData;
    use CheckAttribute;
}
