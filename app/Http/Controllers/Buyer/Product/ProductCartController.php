<?php

declare(strict_types=1);

namespace App\Http\Controllers\Buyer\Product;

use App\Actions\Cart\AddItemAction;
use App\Actions\Cart\DestroyItemAction;
use App\Actions\Cart\LessItemAction;
use App\Domain\Products\Exceptions\ProductExceptions;
use App\Domain\Products\Models\Product;
use App\Http\Controllers\Controller;
use App\Traits\Responses\MakeJsonResponse;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ProductCartController extends Controller
{
    use MakeJsonResponse;

    public function add(Product $product, AddItemAction $addItemAction): JsonResponse
    {
        try {
            $addItemAction->execute(product: $product, cart: auth()->user()->cart);

            return response()->json(data: ['message' => trans(key: 'product.added_to_cart')]);
        } catch (ProductExceptions $exception) {
            report($exception);

            return $this->errorResponse(
                message: $exception->getMessage(),
                code: Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }
    }

    public function less(Product $product, LessItemAction $lessItemAction): JsonResponse
    {
        $lessItemAction->execute(product: $product, cart: auth()->user()->cart);

        return response()->json(data: ['message' => trans(key: 'product.removed_from_cart')]);
    }

    public function destroy(Product $product, DestroyItemAction $destroyItemAction): JsonResponse
    {
        $destroyItemAction->execute(product: $product, cart: auth()->user()->cart);

        return response()->json(data: ['message' => trans(key: 'product.removed_from_cart')]);
    }
}
