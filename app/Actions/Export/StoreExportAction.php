<?php

declare(strict_types=1);

namespace App\Actions\Export;

use App\DataTransferObjects\Export\StoreExportData;
use App\Models\Export;

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
