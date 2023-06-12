<script setup>

import { ref, onMounted } from 'vue';
import PrimaryButton from '@/components/common/PrimaryButton.vue'
import InputError from '@/components/common/InputError.vue'
import UpdatePassword from "@/components/admin/profile/UpdatePassword.vue";

const props = defineProps({
    user: Object,
});

const form = ref({
    name: '',
    last_name: '',
    email: '',
    status: null,
    processing: false,
})

const errors = ref({})

const status = ref({
    message: '',
    success: false,
})

const resetStatus = () => {
    status.value = Object.assign({}, {
        message: '',
        success: false,
    });
}

const submit = () => {

    form.value.processing = true

    axios
        .patch(`/admin/profile`, form.value)
        .then((response) => {
            errors.value = {}
            status.value.message = response.data.message
            status.value.success = true
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

onMounted(() => {
    form.value.name = props.user.name
    form.value.last_name = props.user.last_name
    form.value.email = props.user.email
});

</script>

<template>
    <div class="row">

        <div class="col-md-12">

            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="personal-data-tab" data-bs-toggle="tab"
                        data-bs-target="#personal-data" type="button" role="tab" aria-controls="personal-data"
                        aria-selected="true">Datos Personales</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="security-data-tab" data-bs-toggle="tab" data-bs-target="#security-data"
                        type="button" role="tab" aria-controls="security-data" aria-selected="false">Seguridad</button>
                </li>
            </ul>

            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="personal-data" role="tabpanel"
                    aria-labelledby="personal-data-tab">

                    <form @submit.prevent="submit" class="form-medium">

                        <h1 class="h3 mb-3 fw-normal">Actualizar datos personales</h1>

                        <div class="row g-3">

                            <div v-if="status.success" class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>{{ status.message }}</strong>
                            </div>

                            <div v-if="errors.server">
                                <p class="text-danger fw-bold mb-4">
                                    {{ errors.server }}
                                </p>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-floating">
                                    <input type="text" id="name" class="form-control" placeholder="Nombres"
                                        v-model="form.name">
                                    <label for="name">Nombres</label>
                                    <InputError class="mt-2" :message="errors.name" />
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-floating">
                                    <input type="text" id="last_name" class="form-control" placeholder="Apellidos"
                                        v-model="form.last_name">
                                    <label for="last_name">Apellidos</label>
                                    <InputError class="mt-2" :message="errors.last_name" />
                                </div>

                            </div>


                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="email" id="email" class="form-control" placeholder="nombre@example.com"
                                        autocomplete="username" v-model="form.email">
                                    <label for="email">Correo electrónico</label>
                                    <InputError class="mt-2" :message="errors.email" />
                                </div>
                            </div>

                        </div>

                        <hr class="my-4">

                        <PrimaryButton class="ml-4" :disabled="form.processing">
                            Guardar
                        </PrimaryButton>

                    </form>

                </div>
                <div class="tab-pane fade" id="security-data" role="tabpanel" aria-labelledby="security-data-tab">
                    <UpdatePassword></UpdatePassword>
                </div>
            </div>

        </div>

    </div>
</template>
