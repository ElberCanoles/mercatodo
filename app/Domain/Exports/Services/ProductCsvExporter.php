<?php

declare(strict_types=1);

namespace App\Domain\Exports\Services;

use App\Contracts\Exports\ProductExporterInterface;
use App\Domain\Exports\Actions\StoreExportAction;
use App\Domain\Exports\DataTransferObjects\StoreExportData;
use App\Domain\Exports\Enums\ExportModules;
use App\Domain\Exports\Mails\ExportFailure;
use App\Domain\Exports\Mails\ExportSuccess;
use App\Domain\Products\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Exception;

final class ProductCsvExporter implements ProductExporterInterface
{
    private const PRODUCTS_EXPORT_PATH = 'exports/products/';

    private readonly string $diskName;

    public function __construct()
    {
        $this->diskName = config(key: 'filesystems.default');
    }

    public function headings(): array
    {
        return [
            trans(key: 'product.export_id_head'),
            trans(key: 'product.export_name_head'),
            trans(key: 'product.export_price_head'),
            trans(key: 'product.export_stock_head'),
            trans(key: 'product.export_status_head'),
            trans(key: 'product.export_description_head')
        ];
    }

    public function export(): void
    {
        try {
            $fileName = $this::PRODUCTS_EXPORT_PATH . Str::uuid()->serialize() . ".csv";

            $this->createFile($fileName);

            $file = fopen(filename: Storage::path(path: $fileName), mode: 'w');

            fputcsv(stream: $file, fields: $this->headings());

            Product::query()->chunk(count: 100, callback: function (Collection $products) use ($file) {
                foreach ($products as $product) {
                    fputcsv(stream: $file, fields: [
                        $product->id,
                        $product->name,
                        $product->price,
                        $product->stock,
                        trans(key: $product->status),
                        $product->description
                    ]);
                }
            });

            $this->storeExport(fileName: $fileName);

            fclose(stream: $file);

            Mail::to(config(key: 'admin.email'))->send(new ExportSuccess());
        } catch (Exception $exception) {
            logger()->error(message: 'error during product export', context: [
                'module' => 'ProductCsvExporter.export',
                'message' => $exception->getMessage(),
                'code' => $exception->getCode(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTrace()
            ]);

            Mail::to(config(key: 'admin.email'))->send(new ExportFailure());
        }
    }

    private function createFile(string $fileName): void
    {
        Storage::disk(name: $this->diskName)->put(path: $fileName, contents: "");
    }

    private function storeExport(string $fileName): void
    {
        (new StoreExportAction())->execute(data: StoreExportData::fromArray(data: [
            'module' => ExportModules::PRODUCTS,
            'path' => Storage::url(path: $fileName)
        ]));
    }
}
