<?php

declare(strict_types=1);

namespace App\Domain\Orders\Presenters;

use App\Domain\Orders\Models\Order;

class OrderPresenter
{

    private static ?OrderPresenter $instance = null;

    private Order $order;

    private function __construct()
    {
    }

    public static function getInstance(): self
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function setOrder(Order $order): void
    {
        $this->order = $order;
    }

    public function strPadId(): string
    {
        return str_pad(string: (string)$this->order->id, length: 5, pad_string: '0', pad_type: STR_PAD_LEFT);
    }

    public function amount(): string
    {
        return number_format(num: $this->order->amount, decimal_separator: ',', thousands_separator: '.');
    }

    public function statusTranslated(): string
    {
        return trans($this->order->status);
    }

    public function createdAt(): string
    {
        return $this->order->created_at->format(format: 'd-m-Y');
    }

    public function buyerShowUrl(): string
    {
        return route(name: 'buyer.orders.show', parameters: ['order' => $this->order->id]);
    }

}
