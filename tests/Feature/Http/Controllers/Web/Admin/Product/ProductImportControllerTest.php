<?php

namespace Tests\Feature\Http\Controllers\Web\Admin\Product;

use App\Domain\Products\Jobs\ProductImportJob;
use App\Domain\Users\Enums\Roles;
use App\Domain\Users\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductImportControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private UploadedFile $file;

    public function setUp(): void
    {
        parent::setUp();
        Storage::fake(config(key: 'filesystems.default'));
        $this->seed(class: RoleSeeder::class);
        $this->user = User::factory()->create();
        $this->file = UploadedFile::fake()->create(name: 'products.csv')->size(kilobytes: 500);
    }

    public function test_guest_user_is_redirect_to_login(): void
    {
        $this->get(route(name: 'admin.products.import'))
            ->assertRedirect(uri: '/login');
    }

    public function test_authorized_user_can_call_products_import_route_with_csv_file_attached(): void
    {
        $this->user->assignRole(role: Roles::ADMINISTRATOR);

        $this->actingAs($this->user)
            ->postJson(route(name: 'admin.products.import'), data: ['file' => $this->file])
            ->assertOk();
    }

    public function test_it_should_enqueue_product_import_job(): void
    {
        Queue::fake();

        $this->user->assignRole(role: Roles::ADMINISTRATOR);

        $this->actingAs($this->user)
            ->postJson(route(name: 'admin.products.import'), data: ['file' => $this->file])
            ->assertOk();

        Queue::assertPushed(job: ProductImportJob::class);
    }

    public function test_authorized_user_can_not_upload_file_with_out_csv_extension(): void
    {
        $this->user->assignRole(role: Roles::ADMINISTRATOR);
        $fileTxt = UploadedFile::fake()->create(name: 'products.txt')->size(kilobytes: 500);

        $this->actingAs($this->user)
            ->postJson(route(name: 'admin.products.import'), data: ['file' => $fileTxt])
            ->assertUnprocessable();
    }

    public function test_authorized_user_can_not_upload_file_greater_than_ten_megabytes(): void
    {
        $this->user->assignRole(role: Roles::ADMINISTRATOR);
        $file = UploadedFile::fake()->create(name: 'products.csv')->size(kilobytes: 10001);

        $this->actingAs($this->user)
            ->postJson(route(name: 'admin.products.import'), data: ['file' => $file])
            ->assertUnprocessable();
    }

    public function test_unauthorized_user_can_not_call_products_import_route(): void
    {
        $this->actingAs($this->user)
            ->postJson(route(name: 'admin.products.import'), data: ['file' => $this->file])
            ->assertForbidden();
    }

}
