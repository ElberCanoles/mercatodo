<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Buyer\Product;

use App\Domain\Carts\Actions\AddItemAction;
use App\Domain\Carts\Actions\DestroyItemAction;
use App\Domain\Carts\Actions\LessItemAction;
use App\Domain\Products\Exceptions\ProductExceptions;
use App\Domain\Products\Models\Product;
use App\Domain\Shared\Traits\Responses\MakeJsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ProductCartController extends Controller
{
    use MakeJsonResponse;

    public function add(Product $product, AddItemAction $addItemAction): JsonResponse
    {
        try {
            $addItemAction->execute(product: $product, cart: request()->user()->cart);

            return response()->json(data: ['message' => trans(key: 'product.added_to_cart')]);
        } catch (ProductExceptions $exception) {
            logger()->error(message: 'error to add product to cart', context: [
                'module' => 'ProductCartController.add',
                'message' => $exception->getMessage(),
                'code' => $exception->getCode(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTrace()
            ]);

            return $this->errorResponse(
                message: $exception->getMessage(),
                code: Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }
    }

    public function less(Product $product, LessItemAction $lessItemAction): JsonResponse
    {
        $lessItemAction->execute(product: $product, cart: request()->user()->cart);

        return response()->json(data: ['message' => trans(key: 'product.removed_from_cart')]);
    }

    public function destroy(Product $product, DestroyItemAction $destroyItemAction): JsonResponse
    {
        $destroyItemAction->execute(product: $product, cart: request()->user()->cart);

        return response()->json(data: ['message' => trans(key: 'product.removed_from_cart')]);
    }
}
