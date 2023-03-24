<script setup>

import { ref, onMounted } from 'vue';
import PrimaryButton from '../common/PrimaryButton.vue'
import InputError from './../common/InputError.vue'


const props = defineProps({
    token: String,
    email: String,
});

const form = ref({
    token: '',
    email: '',
    password: '',
    password_confirmation: '',
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
        .post('/reset-password', form.value)
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

onMounted(() => {
    form.value.email = props.email;
    form.value.token = props.token;
});

</script>

<template>
    <form @submit.prevent="submit" class="form-medium">

        <h1 class="h3 mb-3 fw-normal text-center">Restablecer Contraseña</h1>

        <div v-if="!status.success">
            <div class="row g-3">

                <div class="col-12">
                    <div class="form-floating">
                        <input type="email" name="email" id="email" class="form-control" placeholder="nombre@example.com"
                            autocomplete="username" v-model="form.email">
                        <label for="email">Correo electrónico</label>
                        <InputError class="mt-2" :message="errors.email" />
                        <InputError class="mt-2" :message="errors.token" />
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-floating">
                        <input type="password" name="password" id="password" class="form-control" placeholder="password"
                            autocomplete="new-password" v-model="form.password">
                        <label for="password">Contraseña</label>
                        <InputError class="mt-2" :message="errors.password" />
                    </div>
                </div>

                <div class="col-12">
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
                Restablecer Contraseña
            </PrimaryButton>
        </div>

        <div v-else>
            <p class="text-success fw-bold mb-4">
                {{ status.message }}
            </p>
            <a href="/login" class="w-100 btn btn-lg btn-primary ml-4">Acceder</a>
        </div>

    </form>
</template>
