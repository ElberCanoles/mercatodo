<?php

declare(strict_types=1);

namespace App\Traits\Utilities;

trait CheckAttribute
{
    /**
     * Check if an attribute is correctly defined
     */
    protected function isDefined($attribute): bool
    {
        if (isset($attribute) && $attribute != null && ! empty(trim($attribute))) {
            return true;
        } else {
            return false;
        }
    }
}
