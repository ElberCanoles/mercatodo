<?php

declare(strict_types=1);

namespace App\Domain\Exports\DataTransferObjects;

class StoreExportData
{
    public function __construct(
        public string $module,
        public string $path,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new static(
            module: $data['module'],
            path: $data['path']
        );
    }

}