<?php

declare(strict_types=1);

namespace App\Jobs\Product;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class ProductExportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public const PRODUCTS_EXPORT_PATH = 'exports/products/products.csv';

    private readonly string $diskName;

    public function __construct()
    {
        $this->diskName = config(key: 'filesystems.default');
    }

    public function handle(): void
    {
        $headings = [
            trans(key: 'product.export_name_head'),
            trans(key: 'product.export_price_head'),
            trans(key: 'product.export_stock_head'),
            trans(key: 'product.export_status_head'),
            trans(key: 'product.export_description_head')
        ];

        Storage::disk(name: $this->diskName)->put(path: $this::PRODUCTS_EXPORT_PATH, contents: "");

        $filePath = Storage::path(path: $this::PRODUCTS_EXPORT_PATH);

        $file = fopen(filename: $filePath, mode: 'w');

        fputcsv(stream: $file, fields: $headings);

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

        fclose($file);
    }
}
