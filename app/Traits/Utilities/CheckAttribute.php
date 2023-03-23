<?php

declare(strict_types=1);

namespace App\Traits\Utilities;

trait CheckAttribute
{

    protected function isDefined($attribute): bool
    {
        if (isset($attribute) && $attribute != null && !empty(trim($attribute))) {
            return true;
        } else {
            return false;
        }
    }
}
