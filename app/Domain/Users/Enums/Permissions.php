<?php declare(strict_types=1);

namespace App\Domain\Users\Enums;

enum Permissions: string
{
    case PRODUCTS_INDEX = 'products.index';
    case PRODUCTS_CREATE = 'products.create';
    case PRODUCTS_SHOW = 'products.show';
    case PRODUCTS_UPDATE = 'products.update';
    case PRODUCTS_DELETE = 'products.delete';

    case ORDERS_INDEX = 'orders.index';
    case ORDERS_CREATE = 'orders.create';
    case ORDERS_SHOW = 'orders.show';
    case ORDERS_UPDATE = 'orders.update';
    case ORDERS_DELETE = 'orders.delete';

    public static function toArray(): array
    {
        return array_column(array: self::cases(), column_key: 'value');
    }
}
