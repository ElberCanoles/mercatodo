<script setup>

import {ref, onMounted} from 'vue';
import PrimaryButton from '@/components/common/PrimaryButton.vue'
import InputError from '@/components/common/InputError.vue'

const props = defineProps({
    user: Object,
    products: Array,
    total: String
});

const errors = ref({})

const form = ref({
    name: '',
    last_name: '',
    document_type: '',
    document_number: '',
    email: '',
    cell_phone: '',
    department: '',
    city: '',
    address: '',
    processing: false,
})

const submit = () => {

    form.value.processing = true

    axios
        .post(`/buyer/checkout`, form.value)
        .then((response) => {
            errors.value = {}
            window.location.replace(response.data.process_url)
        })
        .catch((exception) => {

            errors.value = {}

            try {
                const dataErrors = exception.response.data.errors

                for (let key in dataErrors) {
                    if (dataErrors.hasOwnProperty(key)) {
                        if (exception.response.status === 500) {
                            toastr.error(dataErrors[key][0], 'Error!', {
                                timeOut: 5000
                            })
                        } else {
                            errors.value[key] = dataErrors[key][0];
                        }
                    }
                }

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
});

</script>

<template>

    <div class="container">

        <div v-if="products.length === 0">
            <p>Aun no ha agregado productos a su carrito.</p>
        </div>

        <main v-else>
            <div class="py-5 text-center">
                <h2>Datos de facturación y envio</h2>
                <p class="lead">Para completar la generación de su orden de compra, por favor diligencie el siguiente
                    formulario,
                    recuerde que no reservaremos stock hasta que se haya realizado el pago.</p>
            </div>

            <div class="row g-5">
                <div class="col-md-5 col-lg-4 order-md-last">
                    <h4 class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-primary">Tu carrito</span>
                        <span class="badge bg-primary rounded-pill">{{ products.length }}</span>
                    </h4>
                    <ul class="list-group mb-3">
                        <li class="list-group-item d-flex justify-content-between lh-sm"
                            v-for="(product, index) in products" :key="index">
                            <div>
                                <h6 class="my-0">{{ product.name }} (x{{ product.quantity }})</h6>
                                <small class="text-muted">${{ product.price }}</small>
                            </div>
                            <span class="text-muted">${{ product.sub_total }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Total (COP)</span>
                            <strong>${{ total }}</strong>
                        </li>
                    </ul>

                </div>
                <div class="col-md-7 col-lg-8">
                    <form @submit.prevent="submit">
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <label class="form-label">Nombres</label>
                                <input type="text" class="form-control" v-model="form.name">
                                <InputError class="mt-2" :message="errors.name"/>
                            </div>

                            <div class="col-sm-6">
                                <label class="form-label">Apellidos</label>
                                <input type="text" class="form-control" v-model="form.last_name">
                                <InputError class="mt-2" :message="errors.last_name"/>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Tipo de documento</label>
                                <select class="form-select" v-model="form.document_type">
                                    <option value="">Elija una opción...</option>
                                    <option value="CC">Cédula de ciudadanía</option>
                                </select>
                                <InputError class="mt-2" :message="errors.document_type"/>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Número de documento</label>
                                <input type="text" class="form-control" v-model="form.document_number">
                                <InputError class="mt-2" :message="errors.document_number"/>
                            </div>

                            <div class="col-6">
                                <label class="form-label">Email</label>
                                <input type="text" class="form-control" placeholder="ejemplo@email.com"
                                       v-model="form.email">
                                <InputError class="mt-2" :message="errors.email"/>
                            </div>

                            <div class="col-6">
                                <label class="form-label">Celular</label>
                                <input type="text" class="form-control" v-model="form.cell_phone">
                                <InputError class="mt-2" :message="errors.cell_phone"/>
                            </div>

                            <div class="col-3">
                                <label class="form-label">Departamento</label>
                                <input type="text" class="form-control" v-model="form.department">
                                <InputError class="mt-2" :message="errors.department"/>
                            </div>

                            <div class="col-3">
                                <label class="form-label">Ciudad</label>
                                <input type="text" class="form-control" v-model="form.city">
                                <InputError class="mt-2" :message="errors.city"/>
                            </div>

                            <div class="col-6">
                                <label class="form-label">Dirección</label>
                                <input type="text" class="form-control" v-model="form.address">
                                <InputError class="mt-2" :message="errors.address"/>
                            </div>


                        </div>

                        <hr class="my-4">

                        <PrimaryButton class="w-100 btn btn-primary btn-lg" :disabled="form.processing">
                            <div v-if="form.processing">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                Procesando...
                            </div>
                            <div v-else>
                                Ir a pagar
                            </div>
                        </PrimaryButton>

                    </form>
                </div>
            </div>
        </main>
    </div>

</template>

<style scoped>

</style>
