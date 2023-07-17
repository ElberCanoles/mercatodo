<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Admin\Product;

use App\Domain\Products\Actions\StoreImportFileAction;
use App\Domain\Products\Jobs\ProductImportJob;
use App\Domain\Shared\Traits\Responses\MakeJsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\ImportRequest;
use Illuminate\Http\JsonResponse;

class ProductImportController extends Controller
{
    use MakeJsonResponse;

    public function __invoke(ImportRequest $request, StoreImportFileAction $action): JsonResponse
    {
        dispatch(job: new ProductImportJob(filePath: $action->execute(file: $request->file(key: 'file'))));

        return $this->showMessage(message: trans(key: 'product.import_done'));
    }
}
