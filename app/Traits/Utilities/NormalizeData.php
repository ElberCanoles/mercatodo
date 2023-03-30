<?php

declare(strict_types=1);

namespace App\Traits\Utilities;

use Illuminate\Support\Facades\Hash;

trait NormalizeData
{

    /**
     * Get formatted string with strtolower
     *
     * @param string $input
     * @return string
     */
    protected function normalizeStringUsingStrtolower(string $input): string
    {
        return strtolower(trim($input));
    }

    /**
     * Get formatted string with ucwords
     *
     * @param string $input
     * @return string
     */
    protected function normalizeStringUsingUcwords(string $input): string
    {
        return ucwords($this->normalizeStringUsingStrtolower($input));
    }

    /**
     * Get formatted string with ucfirst
     *
     * @param string $input
     * @return string
     */
    protected function normalizeStringUsingUcfirst(string $input): string
    {
        return ucfirst($this->normalizeStringUsingStrtolower($input));
    }

    /**
     * Get cipher hash using bcrypt algorithm
     *
     * @param string $input
     * @return string
     */
    protected  function normalizeStringUsingHash(string $input): string
    {
        return Hash::make($input);
    }

}
