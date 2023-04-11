@extends('layouts.authenticated')

@section('content')
    <h2>Gestión de Productos</h2>

    <admin-products-table :statuses="{{ json_encode($statuses) }}">
    </admin-products-table>
@endsection
