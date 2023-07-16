<?php

declare(strict_types=1);

namespace App\Domain\Imports\DataTransferObjects;

class StoreImportData
{
    public function __construct(
        public string $module,
        public string $path,
        public array  $summary,
        public ?array $errors
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            module: $data['module'],
            path: $data['path'],
            summary: $data['summary'],
            errors: $data['errors'] ?? null
        );
    }
}
