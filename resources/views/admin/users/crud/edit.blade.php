@extends('layouts.authenticated')

@section('content')

    <h2>Editar Usuario</h2>

    <admin-users-edit-form
        :user="{{ json_encode($user) }}"
        :statuses="{{ json_encode($statuses) }}">
    </admin-users-edit-form>

@endsection
