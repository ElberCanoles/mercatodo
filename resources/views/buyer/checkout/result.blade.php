@extends('layouts.app')

@section('content')
    <h1>Resultado de la transacción</h1>
    <span>{{ trans($order->status) }}</span>
@endsection
