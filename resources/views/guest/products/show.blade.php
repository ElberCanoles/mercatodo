@extends('layouts.app')

@section('content')
    <guest-products-show
        :product="{{ json_encode($product) }}">
    </guest-products-show>
@endsection
