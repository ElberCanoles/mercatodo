<script setup>


</script>

<template>
    <form class="form-register">

        <h1 class="h3 mb-3 fw-normal text-center">Crear una cuenta</h1>

        <div class="row g-3">
            <div class="col-sm-6">
                <div class="form-floating">
                    <input type="text" name="name" id="name" class="form-control" placeholder="Nombres">
                    <label for="name">Nombres</label>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-floating">
                    <input type="text" name="last_name" id="last_name" class="form-control" placeholder="Apellidos">
                    <label for="last_name">Apellidos</label>
                </div>

            </div>


            <div class="col-12">
                <div class="form-floating">
                    <input type="email" name="email" id="email" class="form-control" placeholder="nombre@example.com">
                    <label for="email">Correo electrónico</label>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-floating">
                    <input type="password" name="password" id="password" class="form-control" placeholder="password">
                    <label for="password">Contraseña</label>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-floating">
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control"
                        placeholder="password">
                    <label for="password_confirmation">Confirmar contraseña</label>
                </div>

            </div>
        </div>

        <hr class="my-4">

        <button class="w-100 btn btn-primary btn-lg" type="submit">Registrarme</button>
    </form>
</template>
