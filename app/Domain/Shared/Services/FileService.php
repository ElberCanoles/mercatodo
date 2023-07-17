<?php

declare(strict_types=1);

namespace App\Domain\Shared\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Exception;

class FileService
{
    private readonly string $diskName;

    public function __construct()
    {
        $this->diskName = config(key: 'filesystems.default');
    }

    public function uploadSingleFile(UploadedFile|string $file, string $relativePath): ?string
    {
        try {
            Storage::disk(name: $this->diskName)->put($relativePath, $file);

            return $this->getFullFilePath($file->hashName(path: $relativePath));
        } catch (Exception $exception) {
            logger()->error(message: 'error uploading single file', context: [
                'module' => 'FileService.uploadSingleFile',
                'message' => $exception->getMessage(),
                'code' => $exception->getCode(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTrace()
            ]);

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

        if (Storage::disk(name: $this->diskName)->exists($relativePath)) {
            Storage::disk(name: $this->diskName)->delete($relativePath);
        }
    }

    public function removeMultipleFiles(array $fullPaths, $fromThePrefix): void
    {
        foreach ($fullPaths as $fullPath) {
            $this->removeSingleFile($fullPath, $fromThePrefix);
        }
    }

    public function getFullFilePath(string $fileNameWithRelativePath): string
    {
        return Storage::url(path: $fileNameWithRelativePath);
    }

    public function getRelativePath(string $fileNameWithFullPath, string $fromThePrefix): string
    {
        $position = strpos($fileNameWithFullPath, $fromThePrefix);

        if ($position !== false) {
            return substr($fileNameWithFullPath, $position);
        } else {
            return $fileNameWithFullPath;
        }
    }
}
