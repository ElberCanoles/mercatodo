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
            if (is_a(object_or_class: $file, class: UploadedFile::class)) {
                if ($fullFilePath = $this->uploadSingleFile(file: $file, relativePath: $relativePath)) {
                    $response[] = $fullFilePath;
                }
            }
        }

        return $response;
    }

    public function removeSingleFile(string $fullPath, $fromThePrefix): void
    {
        $relativePath = $this->getRelativePath(fileNameWithFullPath: $fullPath, fromThePrefix: $fromThePrefix);

        if (Storage::disk(name: 'public')->exists($relativePath)) {
            Storage::disk(name: 'public')->delete($relativePath);
        }
    }

    public function removeMultipleFiles(array $fullPaths, $fromThePrefix): void
    {
        foreach ($fullPaths as $fullPath) {
            $this->removeSingleFile($fullPath, $fromThePrefix);
        }
    }

    private function getFullFilePath(string $fileNameWithRelativePath): string
    {
        return asset(path: "storage/$fileNameWithRelativePath");
    }

    private function getRelativePath(string $fileNameWithFullPath, string $fromThePrefix): string
    {
        $position = strpos($fileNameWithFullPath, $fromThePrefix);

        if ($position !== false) {
            return substr($fileNameWithFullPath, $position);
        } else {
            return $fileNameWithFullPath;
        }
    }
}
