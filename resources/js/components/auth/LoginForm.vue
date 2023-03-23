<script setup>


</script>

<template>
    <form class="form-signin">

        <h1 class="h3 mb-3 fw-normal">Iniciar Sesión</h1>

        <div class="form-floating">
            <input type="email" name="email" id="floatingInput" class="form-control" autocomplete="username"
                placeholder="nombre@example.com">
            <label for="floatingInput">Correo Electrónico</label>
        </div>
        <div class="form-floating">
            <input type="password" name="password" id="floatingPassword" class="form-control" placeholder="password"
                autocomplete="current-password">
            <label for="floatingPassword">Contraseña</label>
        </div>

        <div class="mb-3">
            <label>
                <a href="">¿Olvido su Contraseña?</a>
            </label>
        </div>

        <div class="checkbox mb-3">
            <label>
                <input type="checkbox" value="remember-me"> Recuerdame
            </label>
        </div>
        <button class="w-100 btn btn-lg btn-primary" type="submit">Acceder</button>
    </form>
</template>
