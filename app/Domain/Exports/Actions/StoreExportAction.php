<?php

declare(strict_types=1);

namespace App\Domain\Exports\Actions;

use App\Domain\Exports\DataTransferObjects\StoreExportData;
use App\Domain\Exports\Models\Export;

class StoreExportAction
{
    public function execute(StoreExportData $data): void
    {
        Export::create([
            'module' => $data->module,
            'path' => $data->path
        ]);
    }
}
