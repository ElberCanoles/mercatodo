<?php

declare(strict_types=1);

namespace App\Domain\Imports\Services;

use App\Contracts\Imports\ProductImporterInterface;
use App\Domain\Imports\Actions\StoreImportAction;
use App\Domain\Imports\DataTransferObjects\StoreImportData;
use App\Domain\Imports\Enums\ImportModules;
use App\Domain\Imports\Mails\ImportFailure;
use App\Domain\Imports\Mails\ImportSuccess;
use App\Domain\Products\Actions\StoreProductAction;
use App\Domain\Products\Actions\UpdateProductAction;
use App\Domain\Products\DataTransferObjects\StoreProductData;
use App\Domain\Products\DataTransferObjects\UpdateProductData;
use App\Domain\Products\Enums\ProductStatus;
use App\Domain\Products\Factories\ProductImportValidatorFactory;
use App\Domain\Products\Models\Product;
use App\Domain\Shared\Services\FileService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Exception;

class ProductCsvImporter implements ProductImporterInterface
{
    private const PRODUCTS_IMPORT_PATH = 'imports/products/';

    private const DATA_START_POSITION = 2;

    private array $errors = [];
    private int $createdRecords = 0;
    private int $updatedRecords = 0;
    private int $failedRecords = 0;

    public function __construct(
        private readonly FileService         $fileService,
        private readonly StoreProductAction  $storeProductAction,
        private readonly UpdateProductAction $updateProductAction
    ) {
    }

    public function mapRow(array $row): array
    {
        return [
            'id' => (int)($row[0] ?? 0),
            'name' => Str::ucfirst(Str::lower(value: $row[1] ?? '')),
            'price' => (float)($row[2] ?? 0),
            'stock' => (int)($row[3] ?? 0),
            'status' => $this->mapStatus(status: $row[4] ?? ''),
            'description' => Str::ucfirst(Str::lower(value: $row[5] ?? ''))
        ];
    }

    public function import(string $filePath): void
    {
        try {
            $relativePath = $this->getRelativeImportPath($filePath);

            $file = $this->openFile($relativePath);

            fgetcsv($file);

            $rowPosition = $this::DATA_START_POSITION;

            while (($row = fgetcsv(stream: $file, length: 500))) {
                $row = $this->mapRow($row);

                if ($this->isValidRow($row, $rowPosition)) {
                    if (!$this->productExists($row['id'])) {
                        $this->createProduct($row);
                    } else {
                        $this->updateProduct($row);
                    }
                }

                $rowPosition++;
            }

            $this->saveImportProcessSummary($filePath);

            fclose($file);

            Mail::to(config(key: 'admin.email'))->send(new ImportSuccess());
        } catch (Exception $exception) {
            logger()->error(message: 'error during product import', context: [
                'module' => 'ProductCsvImporter.import',
                'message' => $exception->getMessage(),
                'code' => $exception->getCode(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTrace()
            ]);

            Mail::to(config(key: 'admin.email'))->send(new ImportFailure());
        }
    }

    private function getRelativeImportPath(string $filePath): string
    {
        return $this->fileService->getRelativePath(
            fileNameWithFullPath: $filePath,
            fromThePrefix: self::PRODUCTS_IMPORT_PATH
        );
    }

    private function openFile(string $relativePath)
    {
        return fopen(filename: Storage::path($relativePath), mode: 'r');
    }

    private function productExists(int $productId): bool
    {
        return Product::query()->where(column: 'id', operator: '=', value: $productId)->exists();
    }

    private function createProduct(array $data): void
    {
        $this->storeProductAction->execute(StoreProductData::fromArray($data));
        $this->createdRecords++;
    }

    private function updateProduct(array $data): void
    {
        $product = Product::query()->find($data['id']);
        $product->fill([
            'name' => $data['name'],
            'price' => $data['price'],
            'stock' => $data['stock'],
            'status' => $data['status'],
            'description' => $data['description'],
        ]);

        if ($product->isDirty()) {
            $data['preloaded_images'] = $product->images->toArray();
            $this->updateProductAction->execute(UpdateProductData::fromArray($data), $product);
            $this->updatedRecords++;
        }
    }

    private function isValidRow(array $row, int $rowPosition): bool
    {
        $validator = (new ProductImportValidatorFactory())->make(data: $row);

        if ($validator->fails()) {
            $this->errors[] = [
                trans(key: 'import.invalid_row_in_position', replace: [
                    'position' => $rowPosition
                ]) => $validator->errors()
            ];
            $this->failedRecords++;
            return false;
        }

        return true;
    }

    private function mapStatus(string $status): string
    {
        return match (Str::lower($status)) {
            'disponible', 'available' => ProductStatus::AVAILABLE,
            'no disponible', 'unavailable' => ProductStatus::UNAVAILABLE,
            default => ''
        };
    }

    private function saveImportProcessSummary(string $filePath): void
    {
        (new StoreImportAction())->execute(data: StoreImportData::fromArray(data: [
            'module' => ImportModules::PRODUCTS,
            'path' => $filePath,
            'summary' => [
                'created_records' => $this->createdRecords,
                'updated_records' => $this->updatedRecords,
                'failed_records' => $this->failedRecords
            ],
            'errors' => !empty($this->errors) ? $this->errors : null
        ]));
    }
}
