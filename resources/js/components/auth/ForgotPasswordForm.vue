<script setup>

import { ref } from 'vue';
import PrimaryButton from '../common/PrimaryButton.vue'
import InputError from './../common/InputError.vue'

const form = ref({
    email: '',
    processing: false,
})

const errors = ref({})

const status = ref({
    message: '',
    success: false
})

const submit = () => {

    form.value.processing = true

    axios
        .post('/forgot-password', form.value)
        .then((response) => {
            errors.value = {}
            status.value.message = response.data.message
            status.value.success = true
        })
        .catch((exception) => {

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

        <h1 class="h3 mb-3 fw-normal text-center">¿Olvido su Contraseña?</h1>

        <p class="text-success fw-bold mb-4" v-if="status.success">
           {{ status.message }}
        </p>

        <p class="text-start">
            No hay problema. Simplemente háganos saber su dirección de correo electrónico y le
            enviaremos un enlace de restablecimiento de contraseña que le permitirá elegir una nueva.
        </p>

        <div class="form-floating">
            <input type="email" name="email" id="floatingInput" class="form-control" autocomplete="username"
                placeholder="nombre@example.com" v-model="form.email">
            <label for="floatingInput">Correo Electrónico</label>
            <InputError class="mt-2" :message="errors.email" />
        </div>

        <PrimaryButton class="ml-4" :disabled="form.processing">
            Enviar enlace de restablecimiento
        </PrimaryButton>

    </form>
</template>
