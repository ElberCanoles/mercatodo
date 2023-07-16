<?php

namespace Domain\Exports\Actions;

use App\Domain\Exports\Actions\StoreExportAction;
use App\Domain\Exports\DataTransferObjects\StoreExportData;
use App\Domain\Exports\Models\Export;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreExportActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_store_an_export_record_on_database(): void
    {
        /**
         * @var Export $exportData
         */
        $exportData = Export::factory()->make();

        (new StoreExportAction())->execute(data: StoreExportData::fromArray(data: [
            'module' => $exportData->module,
            'path' => $exportData->path
        ]));

        $this->assertDatabaseCount(table: 'exports', count: 1);
        $this->assertDatabaseHas(table: 'exports', data: [
            'module' => $exportData->module,
            'path' => $exportData->path
        ]);
    }

}
