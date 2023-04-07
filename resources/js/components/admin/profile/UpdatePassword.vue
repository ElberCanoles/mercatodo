<script setup>

import {ref} from 'vue';
import PrimaryButton from '@/components/common/PrimaryButton.vue'
import InputError from '@/components/common/InputError.vue'

const form = ref({
    current_password: '',
    password: '',
    password_confirmation: '',
})

const errors = ref({})

const status = ref({
    message: '',
    success: false,
})

const resetForm = () => {
    form.value = Object.assign({}, {
        current_password: '',
        password: '',
        password_confirmation: '',
    });
}

const resetStatus = () => {
    status.value = Object.assign({}, {
        message: '',
        success: false,
    });
}

const submit = () => {

    form.value.processing = true

    axios
        .patch(`/admin/profile/password`, form.value)
        .then((response) => {
            errors.value = {}
            status.value.message = response.data.message
            status.value.success = true

            resetForm()
        })
        .catch((exception) => {

            errors.value = {}
            resetStatus()

            try {
                const dataErrors = exception.response.data.errors

                for (let key in dataErrors) {
                    if (dataErrors.hasOwnProperty(key)) {
                        errors.value[key] = dataErrors[key][0];
                    }
                }

            } catch (error) {}

        }).finally(() => {
        form.value.processing = false
    });
}


</script>

<template>

    <form @submit.prevent="submit" class="form-medium">

        <h1 class="h3 mb-3 fw-normal">Actualizar contraseña</h1>

        <div class="row g-3">

            <div v-if="status.success">
                <p class="text-success fw-bold mb-4">
                    {{ status.message }}
                </p>
            </div>

            <div v-if="errors.server">
                <p class="text-danger fw-bold mb-4">
                    {{ errors.server }}
                </p>
            </div>

            <div class="col-sm-12">
                <div class="form-floating">
                    <input type="password" id="current_password" class="form-control" placeholder="password"
                           v-model="form.current_password">
                    <label for="current_password">Contraseña actual</label>
                    <InputError class="mt-2" :message="errors.current_password"/>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-floating">
                    <input type="password" id="password" class="form-control"
                           placeholder="password"
                           v-model="form.password">
                    <label for="password">Nueva contraseña</label>
                    <InputError class="mt-2" :message="errors.password"/>
                </div>

            </div>


            <div class="col-6">
                <div class="form-floating">
                    <input type="password" id="password_confirmation" class="form-control"
                           placeholder="password"
                           autocomplete="username" v-model="form.password_confirmation">
                    <label for="password_confirmation">Repita la nueva contraseña</label>
                    <InputError class="mt-2" :message="errors.password_confirmation"/>
                </div>
            </div>

        </div>

        <hr class="my-4">

        <PrimaryButton class="ml-4" :disabled="form.processing">
            Guardar
        </PrimaryButton>

    </form>

</template>
