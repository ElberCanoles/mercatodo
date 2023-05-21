<?php
declare(strict_types=1);

namespace App\Http\Controllers\Guest\Product;

use App\Actions\Cart\AddItemAction;
use App\Actions\Cart\DestroyItemAction;
use App\Actions\Cart\LessItemAction;
use App\Exceptions\ProductExceptions;
use App\Models\Product;
use App\Models\Cart;
use App\Services\Buyer\CartService;
use App\Http\Controllers\Controller;
use App\Traits\Responses\MakeJsonResponse;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;


class ProductCartController extends Controller
{

    use MakeJsonResponse;

    public function __construct(private readonly CartService $cartService)
    {
    }

    public function add(Product $product, AddItemAction $addItemAction): JsonResponse
    {
        try {
            $cookie = $addItemAction->execute(product: $product);

            return response()->json(data: ['message' => trans(key: 'product.added_to_cart')])
                ->cookie(cookie: $cookie);

        } catch (ProductExceptions $exception) {
            return $this->errorResponse(
                message: $exception->getMessage(),
                code: Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }
    }

    public function less(Product $product, LessItemAction $lessItemAction): JsonResponse
    {
        $cookie = $lessItemAction->execute(product: $product);

        return response()->json(data: ['message' => trans(key: 'product.removed_from_cart')])
            ->cookie(cookie: $cookie);
    }

    public function destroy(Product $product, Cart $cart, DestroyItemAction $destroyItemAction): JsonResponse
    {
        $cookie = $destroyItemAction->execute(product: $product, cart: $cart);

        return response()->json(data: ['message' => trans(key: 'product.removed_from_cart')])
            ->cookie(cookie: $cookie);
    }
}
