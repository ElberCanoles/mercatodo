<?php

declare(strict_types=1);

namespace App\Services\Utilities;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileService
{
    public function uploadSingleFile(UploadedFile $file, string $relativePath): ?string
    {
        try {
            Storage::disk(name: 'public')->put($relativePath, $file);

            return $this->getFullFilePath($file->hashName(path: $relativePath));
        } catch (\Throwable $throwable) {
            return null;
        }
    }

    public function uploadMultipleFiles(array $files, string $relativePath): array
    {
        $response = [];

        foreach ($files as $file) {
            if (is_a($file, UploadedFile::class)) {
                if ($fullFilePath = $this->uploadSingleFile(file: $file, relativePath: $relativePath)) {
                    $response[] = $fullFilePath;
                }
            }
        }

        return $response;
    }

    public function removeSingleFile(string $relativePath): void
    {
        //TODO
    }

    public function removeMultipleFiles(array $relativePaths): void
    {
        //TODO
    }

    private function getFullFilePath(string $fileNameWithRelativePath): string
    {
        return asset("storage/$fileNameWithRelativePath");
    }
}
