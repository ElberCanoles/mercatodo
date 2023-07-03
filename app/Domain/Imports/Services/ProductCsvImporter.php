<?php

declare(strict_types=1);

namespace App\Domain\Imports\Services;

use App\Contracts\Imports\ProductImporterInterface;
use App\Contracts\Repository\Product\ProductWriteRepositoryInterface;
use App\Domain\Imports\Enums\ImportModules;
use App\Domain\Imports\Models\Import;
use App\Domain\Products\Enums\ProductStatus;
use App\Domain\Products\Factories\ProductImportValidatorFactory;
use App\Domain\Products\Models\Product;
use App\Domain\Shared\Services\FileService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductCsvImporter implements ProductImporterInterface
{
    private const PRODUCTS_IMPORT_PATH = 'imports/products/';

    private const DATA_START_POSITION = 2;

    private array $errors = [];
    private int $createdRecords = 0;
    private int $updatedRecords = 0;
    private int $failedRecords = 0;

    public function __construct(
        private readonly FileService $fileService,
        private readonly ProductWriteRepositoryInterface $writeRepository)
    {
    }

    public function mapRow(array $row): array
    {
        return [
            'id' => (int)($row[0] ?? 0),
            'name' => Str::ucfirst(Str::lower(value: $row[1] ?? '')),
            'price' => (float)($row[2] ?? 0),
            'stock' => (int)($row[3] ?? 0),
            'status' => $this->mapStatus($row[4]) ?? '',
            'description' => Str::ucfirst(Str::lower(value: $row[5] ?? ''))
        ];
    }

    public function import(string $filePath): void
    {
        $relativePath = $this->getRelativeImportPath($filePath);

        $file = $this->openFile($relativePath);

        fgets($file);

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
        $this->writeRepository->store($data);
        $this->createdRecords++;
    }

    private function updateProduct(array $data): void
    {
        $product = Product::find($data['id']);
        $product->fill([
            'name' => $data['name'],
            'price' => $data['price'],
            'stock' => $data['stock'],
            'status' => $data['status'],
            'description' => $data['description'],
        ]);

        if ($product->isDirty()) {
            $this->writeRepository->update($data, $data['id']);
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
        Import::create([
            'module' => ImportModules::PRODUCTS,
            'path' => $filePath,
            'summary' => [
                'created_records' => $this->createdRecords,
                'updated_records' => $this->updatedRecords,
                'failed_records' => $this->failedRecords
            ],
            'errors' => !empty($this->errors) ? $this->errors : null
        ]);
    }
}
