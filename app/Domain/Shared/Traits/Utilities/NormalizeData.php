<?php

declare(strict_types=1);

namespace App\Domain\Shared\Traits\Utilities;

use Illuminate\Support\Facades\Hash;

trait NormalizeData
{
    /**
     * Get formatted string with strtolower
     */
    protected function normalizeStringUsingStrtolower(string $input = null): ?string
    {
        return $input ? strtolower(trim(preg_replace(pattern: '/ +/', replacement: ' ', subject: $input))) : null;
    }

    /**
     * Get formatted string with ucwords
     */
    protected function normalizeStringUsingUcwords(string $input = null): ?string
    {
        return $input ? ucwords($this->normalizeStringUsingStrtolower($input)) : null;
    }

    /**
     * Get formatted string with ucfirst
     */
    protected function normalizeStringUsingUcfirst(string $input = null): ?string
    {
        return $input ? ucfirst($this->normalizeStringUsingStrtolower($input)) : null;
    }

    /**
     * Get cipher hash using bcrypt algorithm
     */
    protected function normalizeStringUsingHash(string $input): string
    {
        return Hash::make($input);
    }
}
