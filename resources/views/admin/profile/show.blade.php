@extends('layouts.authenticated')

@section('content')

    <h2>Gestionar Perfil</h2>

    <admin-profile-edit-form
        :user="{{ json_encode($user) }}">
    </admin-profile-edit-form>

@endsection
