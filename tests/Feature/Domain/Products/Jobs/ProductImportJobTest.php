<?php

namespace Tests\Feature\Domain\Products\Jobs;

use App\Contracts\Imports\ProductImporterInterface;
use App\Domain\Imports\Mails\ImportSuccess;
use App\Domain\Imports\Models\Import;
use App\Domain\Products\Jobs\ProductImportJob;
use App\Domain\Products\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ProductImportJobTest extends TestCase
{
    use RefreshDatabase;

    private const PRODUCTS_IMPORT_PATH = 'imports/products/';

    private string $diskName;

    public function setUp(): void
    {
        parent::setUp();
        $this->diskName = config(key: 'filesystems.default');
        Storage::fake($this->diskName);
        Mail::fake();
    }

    public function test_it_import_products_csv_file_successfully(): void
    {
        Product::factory()->create([
            'id' => 1,
            'name' => 'First product',
            'description' => 'Content description',
            'price' => 5000,
            'stock' => 5,
            'status' => 'product.unavailable'
        ]);

        $content = 'ID,NAME,PRICE,STOCK,STATUS,DESCRIPTION
1,"First product updated",10000,10,Available,"Content description updated"
2,"Second product created",15000,20,Unavailable,"Content description created"
3,"Third product failed",0,0,"Invalid Status",""';


        $file = UploadedFile::fake()->createWithContent(name: 'products.csv', content: $content);
        $filePath = Storage::disk(name: $this->diskName)->put($this::PRODUCTS_IMPORT_PATH, $file);

        (new ProductImportJob($filePath))->handle(resolve(name: ProductImporterInterface::class));

        /**
         * @var Import $import
         */
        $import = Import::query()->first();

        $this->assertDatabaseCount(table: 'products', count: 2);
        $this->assertDatabaseCount(table: 'imports', count: 1);

        $this->assertEquals(
            expected: ["failed_records" => 1, "created_records" => 1, "updated_records" => 1],
            actual: $import->summary
        );

        $this->assertDatabaseHas(table: 'imports', data: [
            'module' => 'import.products_module'
        ]);

        $this->assertDatabaseMissing(table: 'products', data: [
            'name' => 'First product',
            'description' => 'Content description',
            'price' => 5000,
            'stock' => 5,
            'status' => 'product.unavailable'
        ]);

        $this->assertDatabaseMissing(table: 'products', data: [
            'name' => 'Third product failed',
        ]);

        $this->assertDatabaseHas(table: 'products', data: [
            'name' => 'First product updated',
            'description' => 'Content description updated',
            'price' => 10000,
            'stock' => 10,
            'status' => 'product.available'
        ]);

        $this->assertDatabaseHas(table: 'products', data: [
            'name' => 'Second product created',
            'description' => 'Content description created',
            'price' => 15000,
            'stock' => 20,
            'status' => 'product.unavailable'
        ]);

        Mail::assertSent(mailable: ImportSuccess::class);
    }
}
