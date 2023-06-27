<?php

declare(strict_types=1);

namespace App\Contracts\Exports;


interface ProductExporterInterface
{
    public function headings(): array;

    public function export(): void;
}
