@extends('layouts.app')

@section('content')
    <div class="p-5 mb-4 bg-light rounded-3">
        <div class="container-fluid py-5">
            <p class="col-md-8 fs-4">Bienvenido a la tienda online donde encontraras de todo y para todos, en Merca Todo
                somos
                expertos en ir de compras, asi que te acompañamos para que encuentres eso que tanto necesitas al mejor
                precio y de forma segura, ¡Vamos a ello!</p>
            <a class="btn btn-primary btn-lg" href="{{ route('buyer.products.index') }}">Navegar por la tienda</a>
        </div>
    </div>

    <div class="row align-items-md-stretch">
        <div class="col-md-6">
            <div class="h-100 p-5 text-white bg-dark rounded-3">
                <h2>Tu Tienda Contigo</h2>
                <p>Desde tu panel de usuario, podras gestionar tu perfil, revisar tus ordenes de compra y mucho más, en
                    Merca Todo creamos todo un mundo de posibilidades para ti ¡te esperamos!</p>
                <a href="{{ route('login') }}" class="btn btn-outline-light" type="button">Acceder</a>
            </div>
        </div>
        <div class="col-md-6">
            <div class="h-100 p-5 bg-light border rounded-3">
                <h2>Vive la Experiencia</h2>
                <p>Si aun no te has registrado, por favor crea tu cuenta, solo debes diligenciar el formulario, con los
                    datos solicitados, y listo crearemos un usario para ti
                    con el cual podras disfrutar de la experiencia Merca Todo.
                </p>
                <a href="{{ route('register') }}" class="btn btn-outline-secondary" type="button">Crear una cuenta</a>
            </div>
        </div>
    </div>
@endsection
