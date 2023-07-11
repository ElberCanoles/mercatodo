<?php

declare(strict_types=1);

namespace App\Domain\Products\DataTransferObjects;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class UpdateProductData
{
    public function __construct(
        public string $name,
        public float  $price,
        public int    $stock,
        public string $status,
        public string $description,
        public ?array $preloaded_images,
        public ?array $images
    ) {
    }

    public static function fromRequest(FormRequest $request): self
    {
        return new self(
            name: Str::ucfirst(Str::lower($request->input(key: 'name'))),
            price: (float)$request->input(key: 'price'),
            stock: (int)$request->input(key: 'stock'),
            status: $request->input(key: 'status'),
            description: Str::ucfirst(Str::lower($request->input(key: 'description'))),
            preloaded_images: $request->input(key: 'preloaded_images') ?? null,
            images: $request->allFiles()['images'] ?? null
        );
    }

    public static function fromArray(array $data): self
    {
        return new self(
            name: Str::ucfirst(Str::lower($data['name'])),
            price: (float)$data['price'],
            stock: (int)$data['stock'],
            status: $data['status'],
            description: Str::ucfirst(Str::lower($data['description'])),
            preloaded_images: $data['preloaded_images'] ?? null,
            images: $data['images'] ?? null
        );
    }
}
