<?php

declare(strict_types=1);

namespace App\Traits\Utilities;

use Illuminate\Support\Facades\Hash;

trait NormalizeData
{

    /**
     * Get formatted string with strtolower
     *
     * @param string|null $input
     * @return string|null
     */
    protected function normalizeStringUsingStrtolower(string $input = null): ?string
    {
        return $input ? strtolower(trim($input)) : null;
    }


    /**
     * Get formatted string with ucwords
     *
     * @param string|null $input
     * @return string|null
     */
    protected function normalizeStringUsingUcwords(string $input = null): ?string
    {
        return $input ? ucwords($this->normalizeStringUsingStrtolower($input)) : null;
    }


    /**
     * Get formatted string with ucfirst
     *
     * @param string|null $input
     * @return string|null
     */
    protected function normalizeStringUsingUcfirst(string $input = null): ?string
    {
        return $input ? ucfirst($this->normalizeStringUsingStrtolower($input)) : null;
    }


    /**
     * Get cipher hash using bcrypt algorithm
     *
     * @param string $input
     * @return string
     */
    protected function normalizeStringUsingHash(string $input): string
    {
        return Hash::make($input);
    }

}
