@extends('layouts.authenticated')

@section('content')
    <h2>Editar producto</h2>
    <hr>
    <admin-products-edit-form 
        :product="{{ json_encode($product) }}" 
        :statuses="{{ json_encode($statuses) }}">
    </admin-products-edit-form>
@endsection
