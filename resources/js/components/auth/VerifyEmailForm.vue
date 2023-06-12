<script setup>

import { ref } from 'vue';
import PrimaryButton from '../common/PrimaryButton.vue'

const form = ref({
    email: '',
    processing: false,
})

const status = ref(false)

const submit = () => {

    form.value.processing = true

    axios
        .post('/email/verification-notification', form.value)
        .then((response) => {
            status.value = true
        })
        .catch((exception) => {


        }).finally(() => {
            form.value.processing = false
        });

}

</script>

<template>
    <form @submit.prevent="submit" class="form-medium">

        <h1 class="h3 mb-3 fw-normal text-center">Verificar Email</h1>

        <p class="text-success fw-bold mb-4" v-if="status">
            Se ha enviado un nuevo enlace de verificación a la dirección de correo electrónico que proporcionó durante el
            registro.
        </p>

        <p class="text-start">
            Antes de continuar, ¿podría verificar su dirección de correo electrónico haciendo clic en el enlace que le
            acabamos de enviar? Si no recibiste el correo electrónico, con gusto te enviaremos otro.
        </p>

        <div class="col-md-6">
            <PrimaryButton class="ml-4" :disabled="form.processing">
                Reenviar Correo
            </PrimaryButton>
        </div>

    </form>
</template>
