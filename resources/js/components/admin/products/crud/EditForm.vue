<script setup>

import { ref, onMounted } from 'vue';
import PrimaryButton from '@/components/common/PrimaryButton.vue'
import InputError from '@/components/common/InputError.vue'

const props = defineProps({
    product: Object,
    statuses: {},
});

const form = ref({
    name: '',
    description: '',
    price: '',
    stock: '',
    status: '',
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
        .put(`/admin/products/${props.product.id}`, form.value)
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

            } catch (error) {
            }

        }).finally(() => {
            form.value.processing = false
        });
}

onMounted(() => {
    form.value.name = props.product.name
    form.value.description = props.product.description
    form.value.price = props.product.price
    form.value.stock = props.product.stock
    form.value.status = props.product.status
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
                            <input type="text" id="name" class="form-control" placeholder="Nombre" v-model="form.name">
                            <label for="name">Nombre</label>
                            <InputError class="mt-2" :message="errors.name" />
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-floating">
                            <input type="number" step="0.01" id="price" class="form-control" placeholder="Precio"
                                v-model="form.price">
                            <label for="price">Precio</label>
                            <InputError class="mt-2" :message="errors.price" />
                        </div>

                    </div>

                    <div class="col-sm-6">
                        <div class="form-floating">
                            <input type="number" id="stock" class="form-control" placeholder="Stock" v-model="form.stock">
                            <label for="stock">Stock</label>
                            <InputError class="mt-2" :message="errors.stock" />
                        </div>

                    </div>

                    <div class="col-sm-6">
                        <div class="form-floating">
                            <select class="form-select" id="status" v-model="form.status">
                                <option value="">Elija una opción...</option>
                                <option v-for="(status, index) in statuses" :key="index" :value="status.key">
                                    {{ status.value }}
                                </option>
                            </select>
                            <label for="status">Estado</label>
                            <InputError class="mt-2" :message="errors.status" />
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-floating">
                            <textarea id="description" class="form-control" v-model="form.description">
                                        </textarea>
                            <label for="description">Descripción</label>
                            <InputError class="mt-2" :message="errors.description" />
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
