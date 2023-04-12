@extends('layouts.authenticated')

@section('content')
    <h2>Crear nuevo producto</h2>
    <hr>
    <admin-products-create-form :statuses="{{ json_encode($statuses) }}">
    </admin-products-create-form>
@endsection
