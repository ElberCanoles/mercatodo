<?php

namespace App\Domain\Imports\Actions;

use App\Domain\Imports\DataTransferObjects\StoreImportData;
use App\Domain\Imports\Models\Import;

class StoreImportAction
{

    public function execute(StoreImportData $data): void
    {
        Import::create([
            'module' => $data->module,
            'path' => $data->path,
            'summary' => $data->summary,
            'errors' => $data->errors
        ]);
    }

}
