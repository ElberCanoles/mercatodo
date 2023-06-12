@extends('layouts.authenticated')

@section('content')

    <h2>Gestionar Perfil</h2>

    <buyer-profile-edit-form
        :user="{{ json_encode($user) }}">
    </buyer-profile-edit-form>

@endsection
