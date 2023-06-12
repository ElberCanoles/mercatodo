@extends('layouts.app')

@section('content')
    <buyer-checkout-form
        :user="{{ json_encode($user) }}"
        :products="{{ json_encode($products) }}"
        :total="{{ json_encode($total) }}"
        :order="{{ json_encode($order) }}">
    </buyer-checkout-form>
@endsection
