@extends('layouts.authenticated')

@section('content')
    <h2>Gestión de Productos</h2>
    <hr>
    <div class="d-flex flex-row">
        <a class="btn btn-primary p-2" href="{{ route('admin.products.create') }}">Crear nuevo</a>
    </div>
    <admin-products-table :statuses="{{ json_encode($statuses) }}">
    </admin-products-table>
@endsection
