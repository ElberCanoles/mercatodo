@extends('layouts.authenticated')

@section('content')
    <div class="container">
        <h2 class="text-center mb-4">Detalles de importación</h2>
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Módulo:</h5>
                <p class="card-text h6">{{ trans($import->module) }}</p>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Registros creados</h5>
                        <p class="card-text">{{ $import->summary['created_records'] }}</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Registros actualizados</h5>
                        <p class="card-text">{{ $import->summary['updated_records'] }}</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Registros con errores</h5>
                        <p class="card-text">{{ $import->summary['failed_records'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        @if($import->errors != null)
            <h5 class="mt-4">Detalles de errores:</h5>
            <ul class="list-group">
                @foreach($import->errors as $error)
                    @foreach($error as $errorMessage => $details)
                        <li class="list-group-item">
                            <h6 class="card-subtitle mt-2 mb-2">{{ $errorMessage }}</h6>
                            <ul>
                                @foreach($details as $field => $messages)
                                    @foreach($messages as $message)
                                        <li>{{ $message }}</li>
                                    @endforeach
                                @endforeach
                            </ul>
                        </li>
                    @endforeach
                @endforeach
            </ul>
        @endif

        <h5 class="mt-4">Archivo procesado:</h5>
        <a class="btn btn-primary" target="_blank" href="{{ $import->path }}">
            <span data-feather="download"></span>
            Descargar
        </a>
    </div>
@endsection
