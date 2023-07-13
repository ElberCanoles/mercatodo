<?php

namespace Domain\Products\Jobs;

use App\Domain\Products\Models\Product;
use App\Domain\Exports\Services\ProductCsvExporter;
use App\Domain\Products\Jobs\ProductExportJob;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductExportJobTest extends TestCase
{
    use RefreshDatabase;

    private array $headings;

    private string $diskName;

    private const PRODUCTS_EXPORT_PATH = 'exports/products/';

    public function setUp(): void
    {
        parent::setUp();
        $this->diskName = config(key: 'filesystems.default');
        Storage::fake($this->diskName);
        Product::factory(count: 10)->create();
        $this->headings = [
            trans(key: 'product.export_id_head'),
            trans(key: 'product.export_name_head'),
            trans(key: 'product.export_price_head'),
            trans(key: 'product.export_stock_head'),
            trans(key: 'product.export_status_head'),
            trans(key: 'product.export_description_head')
        ];
    }

    public function test_it_generate_export_csv_file_successfully(): void
    {

        (new ProductExportJob())->handle(new ProductCsvExporter());

        $files = Storage::disk($this->diskName)->files(directory: $this::PRODUCTS_EXPORT_PATH);

        $matchingFiles = collect($files)->filter(function ($file) {
            return pathinfo($file, flags: PATHINFO_EXTENSION) === 'csv';
        });

        $csvFileGenerated = Storage::disk($this->diskName)->get($matchingFiles->first());

        $this->assertTrue($matchingFiles->isNotEmpty());
        $this->assertCount(expectedCount: 1, haystack: $matchingFiles);
        $this->assertStringContainsString(
            needle: $this->headings[0] . ','
            . $this->headings[1] . ','
            . $this->headings[2] . ','
            . $this->headings[3] . ','
            . $this->headings[4] . ','
            . $this->headings[5],
            haystack: $csvFileGenerated
        );

        /**
         * @var Product $product
         */
        foreach (Product::all() as $product) {
            $this->assertStringContainsString(needle: $product->id, haystack: $csvFileGenerated);
            $this->assertStringContainsString(needle: $product->name, haystack: $csvFileGenerated);
            $this->assertStringContainsString(needle: $product->price, haystack: $csvFileGenerated);
            $this->assertStringContainsString(needle: $product->stock, haystack: $csvFileGenerated);
            $this->assertStringContainsString(needle: trans(key: $product->status), haystack: $csvFileGenerated);
            $this->assertStringContainsString(needle: $product->description, haystack: $csvFileGenerated);
        }
    }

}
