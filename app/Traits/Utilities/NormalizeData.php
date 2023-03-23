<?php

declare(strict_types=1);

namespace App\Traits\Utilities;

trait NormalizeData
{

    protected function normalizeStringUsingStrtolower(string $input): string
    {
        return strtolower(trim($input));
    }

    protected function normalizeStringUsingUcwords(string $input): string
    {
        return ucwords($this->normalizeStringUsingStrtolower($input));
    }

    protected function normalizeStringUsingUcfirst(string $input): string
    {
        return ucfirst($this->normalizeStringUsingStrtolower($input));
    }

}
