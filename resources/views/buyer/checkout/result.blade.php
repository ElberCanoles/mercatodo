@extends('layouts.app')

@section('content')
    @if (isset($status))
        <h1>Resultado de la transacción</h1>

        @switch($status)
            @case(App\Enums\Payment\PaymentStatus::PAID)
                <div class="alert alert-success" role="alert">
                    Pago: <span class="alert-link">{{ trans($status) }}</span>
                </div>
            @break

            @case(App\Enums\Payment\PaymentStatus::PENDING)
                <div class="alert alert-warning" role="alert">
                    Pago: <span class="alert-link">{{ trans($status) }}</span>
                </div>
            @break

            @case(App\Enums\Payment\PaymentStatus::REJECTED)
                <div class="alert alert-danger" role="alert">
                    Pago: <span class="alert-link">{{ trans($status) }}</span>
                </div>
            @break

            @default
        @endswitch
    @else
        <h1>No ahi información de pagos</h1>
    @endif

    <a href="{{ route('buyer.orders.index') }}" class="btn btn-primary">Ir a mis ordenes</a>

@endsection
