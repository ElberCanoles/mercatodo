<?php

declare(strict_types=1);

namespace App\Services\Imports;

use App\Contracts\Imports\ProductImporterInterface;
use App\Contracts\Repository\Product\ProductWriteRepositoryInterface;
use App\Enums\Import\ImportModules;
use App\Enums\Product\ProductStatus;
use App\Models\Import;
use App\Models\Product;
use App\Services\Utilities\FileService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

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

        $this->logOutput($filePath);

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
        $validator = Validator::make($row, [
            'name' => ['required', 'string', 'max:100', Rule::unique(table: 'products')->ignore($row['id'])],
            'price' => ['required', 'numeric', 'min:1', 'max:99999999'],
            'stock' => ['required', 'integer', 'min:0', 'max:9999999'],
            'status' => ['required', Rule::in(ProductStatus::asArray())],
            'description' => ['required', 'string', 'max:500'],
        ], [
            'name.required' => 'El nombre es requerido',
            'name.string' => 'El nombre debe ser una cadena de texto',
            'name.max' => 'El nombre no puede contener más de 100 caracteres',
            'name.unique' => 'Ya existe un producto registrado con este nombre',

            'price.required' => 'El precio es requerido',
            'price.numeric' => 'Debe ingresar un valor númerico',
            'price.min' => 'El precio debe ser al menos 1',
            'price.max' => 'El precio no debe ser mayor que 99999999',

            'stock.required' => 'El stock es requerido',
            'stock.integer' => 'Debe ingresar un número entero',
            'stock.min' => 'El stock debe ser al menos 0',
            'stock.max' => 'El stock no debe ser mayor que 9999999',

            'status.required' => 'El estado es requerido',
            'status.in' => 'El estado no es valido',

            'description.required' => 'La descripción es requerida',
            'description.max' => 'La descripción no puede contener más de 500 caracteres',
        ]);

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

    private function logOutput(string $filePath): void
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
