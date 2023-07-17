<?php

declare(strict_types=1);

namespace App\Contracts\Imports;

interface ImporterInterface
{
    public function mapRow(array $row): array;

    public function import(string $filePath): void;
}
