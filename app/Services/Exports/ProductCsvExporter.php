<?php

declare(strict_types=1);

namespace App\Services\Exports;

use App\Contracts\Exports\ProductExporter;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;

final class ProductCsvExporter implements ProductExporter
{
    private const PRODUCTS_EXPORT_PATH = 'exports/products/products.csv';

    private readonly string $diskName;

    public function __construct()
    {
        $this->diskName = config(key: 'filesystems.default');
    }

    public function headings(): array
    {
        return [
            trans(key: 'product.export_name_head'),
            trans(key: 'product.export_price_head'),
            trans(key: 'product.export_stock_head'),
            trans(key: 'product.export_status_head'),
            trans(key: 'product.export_description_head')
        ];
    }

    public function export(): void
    {
        $this->createFile();

        $file = fopen(filename: Storage::path(path: $this::PRODUCTS_EXPORT_PATH), mode: 'w');

        fputcsv(stream: $file, fields: $this->headings());

        Product::query()->chunk(count: 100, callback: function (Collection $products) use ($file) {
            foreach ($products as $product) {
                fputcsv(stream: $file, fields: [
                    $product->name,
                    $product->price,
                    $product->stock,
                    trans(key: $product->status),
                    $product->description
                ]);
            }
        });

        fclose(stream: $file);
    }

    private function createFile(): void
    {
        Storage::disk(name: $this->diskName)->put(path: $this::PRODUCTS_EXPORT_PATH, contents: "");
    }

}
