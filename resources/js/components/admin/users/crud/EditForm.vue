<script setup>

import {ref, onMounted} from 'vue';
import PrimaryButton from '@/components/common/PrimaryButton.vue'
import InputError from '@/components/common/InputError.vue'

const props = defineProps({
    user: Object,
    statuses: {},
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
        .put(`/admin/users/${props.user.id}`, form.value)
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
    form.value.name = props.user.name
    form.value.last_name = props.user.last_name
    form.value.email = props.user.email
    form.value.status = props.user.status
});

</script>

<template>

    <div class="row">

        <div class="col-md-12">

            <form @submit.prevent="submit" class="form-medium">

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

                    <div class="col-sm-6">
                        <div class="form-floating">
                            <input type="text" id="name" class="form-control" placeholder="Nombres"
                                   v-model="form.name">
                            <label for="name">Nombres</label>
                            <InputError class="mt-2" :message="errors.name"/>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-floating">
                            <input type="text" id="last_name" class="form-control"
                                   placeholder="Apellidos"
                                   v-model="form.last_name">
                            <label for="last_name">Apellidos</label>
                            <InputError class="mt-2" :message="errors.last_name"/>
                        </div>

                    </div>


                    <div class="col-12">
                        <div class="form-floating">
                            <input type="email" id="email" class="form-control"
                                   disabled
                                   v-model="form.email">
                            <label for="email">Correo electrónico</label>
                            <InputError class="mt-2" :message="errors.email"/>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-floating">
                            <select class="form-select" id="status" v-model="form.status">
                                <option v-for="(status, index) in statuses" :key="index" :value="status.key">
                                    {{ status.value }}
                                </option>
                            </select>
                            <label for="status">Estado</label>
                            <InputError class="mt-2" :message="errors.status"/>
                        </div>
                    </div>

                </div>

                <hr class="my-4">

                <PrimaryButton class="ml-4" :disabled="form.processing">
                    Guardar
                </PrimaryButton>

            </form>

        </div>

    </div>
</template>
