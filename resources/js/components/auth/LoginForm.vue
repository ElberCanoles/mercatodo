<script setup>

import { ref } from 'vue';
import InputError from './../common/InputError.vue'
import PrimaryButton from './../common/PrimaryButton.vue'

const form = ref({
    email: '',
    password: '',
    remember: false,
    processing: false,
})

const errors = ref({})

const submit = () => {

    form.value.processing = true

    axios
        .post('/login', form.value)
        .then((response) => {
            window.location.replace(response.request.responseURL);
        })
        .catch((exception) => {

            if (exception.response.status == 403) {
                window.location.replace(exception.response.request.responseURL)
            }
            
            errors.value = {}
            try {
                const dataErrors = exception.response.data.errors

                for (let key in dataErrors) {
                    if (dataErrors.hasOwnProperty(key)) {
                        errors.value[key] = dataErrors[key][0];
                    }
                }

            } catch (error) {

            }

        }).finally(() => {
            form.value.processing = false
        });

}

</script>

<template>
    <form @submit.prevent="submit" class="form-narrow">

        <h1 class="h3 mb-3 fw-normal">Iniciar Sesión</h1>

        <div class="form-floating">
            <input type="email" name="email" id="floatingInput" class="form-control" autocomplete="username"
                placeholder="nombre@example.com" v-model="form.email">
            <label for="floatingInput">Correo Electrónico</label>
            <InputError class="mt-2" :message="errors.email" />
        </div>
        <div class="form-floating">
            <input type="password" name="password" id="floatingPassword" class="form-control" placeholder="password"
                autocomplete="current-password" v-model="form.password">
            <label for="floatingPassword">Contraseña</label>
            <InputError class="mt-2" :message="errors.password" />
        </div>

        <div class="mb-3">
            <label>
                <a href="/forgot-password">¿Olvido su Contraseña?</a>
            </label>
        </div>

        <div class="checkbox mb-3">
            <label>
                <input type="checkbox" value="on" v-model="form.remember"> Recuerdame
            </label>
        </div>

        <PrimaryButton class="ml-4" :disabled="form.processing">
            Acceder
        </PrimaryButton>

    </form>
</template>
