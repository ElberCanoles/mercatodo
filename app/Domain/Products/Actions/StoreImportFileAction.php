<?php

declare(strict_types=1);

namespace App\Domain\Products\Actions;

use App\Domain\Shared\Services\FileService;
use Illuminate\Http\UploadedFile;

class StoreImportFileAction
{
    private const PRODUCTS_IMPORT_PATH = 'imports/products/';

    public function __construct(private readonly FileService $fileService)
    {
    }

    public function execute(UploadedFile $file): string
    {
        return $this->fileService->uploadSingleFile(file: $file, relativePath: $this::PRODUCTS_IMPORT_PATH);
    }
}
