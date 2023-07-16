<?php

namespace Domain\Imports\Actions;

use App\Domain\Imports\Actions\StoreImportAction;
use App\Domain\Imports\DataTransferObjects\StoreImportData;
use App\Domain\Imports\Models\Import;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreImportActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_store_an_import_record_on_database(): void
    {
        /**
         * @var Import $importData
         */
        $importData = Import::factory()->make();

        (new StoreImportAction())->execute(data: StoreImportData::fromArray(data: [
            'module' => $importData->module,
            'path' => $importData->path,
            'summary' => $importData->summary,
            'errors' => $importData->errors
        ]));

        $this->assertDatabaseCount(table: 'imports', count: 1);
        $this->assertDatabaseHas(table: 'imports', data: [
            'module' => $importData->module,
            'path' => $importData->path,
            'errors' => $importData->errors
        ]);
    }
}
