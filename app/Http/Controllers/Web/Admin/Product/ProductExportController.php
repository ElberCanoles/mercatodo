<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Admin\Product;

use App\Domain\Shared\Traits\Responses\MakeJsonResponse;
use App\Http\Controllers\Controller;
use App\Jobs\Product\ProductExportJob;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductExportController extends Controller
{
    use MakeJsonResponse;

    public function __invoke(Request $request): JsonResponse
    {
        ProductExportJob::dispatch();
        return $this->showMessage(message: trans(key: 'product.export_done'));
    }
}
