@extends('layouts.app')

@section('content')
    <div class="body-medium">
        <reset-password-form :token="{{json_encode($request->route('token'))}}" :email="{{json_encode($request->email)}}"></reset-password-form>
    </div>
@endsection