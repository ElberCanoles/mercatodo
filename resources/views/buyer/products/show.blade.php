@extends('layouts.app')

@section('content')
    <buyer-products-show
        :product="{{ json_encode($product) }}">
    </buyer-products-show>
@endsection
