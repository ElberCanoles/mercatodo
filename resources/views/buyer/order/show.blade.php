@extends('layouts.authenticated')

@section('content')
    <div class="album py-5 bg-light">
        <div class="container">

            <h2>Orden de compra: #{{ str_pad((string) $order->id, 5, '0', STR_PAD_LEFT) }}</h2>

            <h3 class="mt-4">Resumen de la orden:</h3>
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">Producto</th>
                    <th scope="col">Precio</th>
                    <th scope="col">Cantidad</th>
                    <th scope="col" class="text-end">Sub total</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($order->products as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td>${{ number_format(num: $product->price, decimal_separator: ',', thousands_separator: '.') }}
                        </td>
                        <th scope="row">
                            {{ $product->pivot->quantity }}
                        </th>
                        <th scope="row" class="text-end">
                            ${{ number_format(num: $product->total, decimal_separator: ',', thousands_separator: '.') }}
                        </th>
                    </tr>
                @endforeach
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <th scope="row" class="text-end">
                        Total:
                        ${{ number_format(num: $order->amount, decimal_separator: ',', thousands_separator: '.') }}
                    </th>
                </tr>
                </tbody>

            </table>
            <h5>Estado: {{ trans($order->status) }}</h5>
            @if ($order->status != \App\Domain\Orders\Enums\OrderStatus::CONFIRMED)
                <a class="w-100 btn btn-lg btn-primary"
                   href="{{ route('buyer.orders.retry.payment', ['order' => $order->id]) }}">
                    Reintentar Pago
                </a>
            @endif
        </div>
    </div>
@endsection
