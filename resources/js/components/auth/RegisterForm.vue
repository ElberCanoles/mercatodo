<script setup>

import { ref } from 'vue';
import InputError from './../common/InputError.vue'
import PrimaryButton from './../common/PrimaryButton.vue'

const form = ref({
    name: '',
    last_name: '',
    email: '',
    password: '',
    password_confirmation: '',
    processing: false,
})

const errors = ref({})

const submit = () => {

    form.value.processing = true

    axios
        .post('/register', form.value)
        .then((response) => {
            window.location.replace(response.request.responseURL)
        })
        .catch((exception) => {

            if (exception.response.status == 403) {
                window.location.replace(exception.response.request.responseURL)
            }

            errors.value = {}
            try {
                const dataErrors = exception.response.data.errors

                $.each(dataErrors, function (key, value) {
                    errors.value[key] = value[0]
                })

            } catch (error) {

            }

        }).finally(() => {
            form.value.processing = false
        });
}

</script>

<template>
    <form @submit.prevent="submit" class="form-medium">

        <h1 class="h3 mb-3 fw-normal text-center">Crear una cuenta</h1>

        <div class="row g-3">
            <div class="col-sm-6">
                <div class="form-floating">
                    <input type="text" name="name" id="name" class="form-control" placeholder="Nombres" v-model="form.name">
                    <label for="name">Nombres</label>
                    <InputError class="mt-2" :message="errors.name" />
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-floating">
                    <input type="text" name="last_name" id="last_name" class="form-control" placeholder="Apellidos"
                        v-model="form.last_name">
                    <label for="last_name">Apellidos</label>
                    <InputError class="mt-2" :message="errors.last_name" />
                </div>

            </div>


            <div class="col-12">
                <div class="form-floating">
                    <input type="email" name="email" id="email" class="form-control" placeholder="nombre@example.com"
                        autocomplete="username" v-model="form.email">
                    <label for="email">Correo electrónico</label>
                    <InputError class="mt-2" :message="errors.email" />
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-floating">
                    <input type="password" name="password" id="password" class="form-control" placeholder="password"
                        autocomplete="new-password" v-model="form.password">
                    <label for="password">Contraseña</label>
                    <InputError class="mt-2" :message="errors.password" />
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-floating">
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control"
                        placeholder="password" autocomplete="new-password" v-model="form.password_confirmation">
                    <label for="password_confirmation">Confirmar contraseña</label>
                    <InputError class="mt-2" :message="errors.password_confirmation" />
                </div>

            </div>
        </div>

        <hr class="my-4">

        <PrimaryButton class="ml-4" :disabled="form.processing">
            Registarme
        </PrimaryButton>

    </form>
</template>
