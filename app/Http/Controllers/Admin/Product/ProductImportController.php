<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\ImportRequest;
use App\Traits\Responses\MakeJsonResponse;
use Illuminate\Http\JsonResponse;

class ProductImportController extends Controller
{
    use MakeJsonResponse;

    public function __invoke(ImportRequest $request): JsonResponse
    {
        return $this->showMessage(message: trans(key: 'product.import_done'));
    }
}
