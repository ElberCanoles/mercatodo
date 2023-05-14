<script setup>

import { ref } from 'vue';
import PrimaryButton from '@/components/common/PrimaryButton.vue'
import InputError from '@/components/common/InputError.vue'

const props = defineProps({
    statuses: {},
});


const files = ref([])

const images = ref([])

const form = ref({
    name: '',
    description: '',
    price: '',
    stock: '',
    status: '',
    images: [],
    processing: false,
})

const errors = ref({})

const status = ref({
    message: '',
    success: false,
})

const addImagesToGallery = async (e) => {
    const filesToAdd = e.target.files

    const promises = Array.from(filesToAdd).map(async (file) => {
        files.value.push(file)

        const reader = new FileReader()

        const promise = new Promise((resolve) => {
            reader.onload = () => resolve(reader.result)
        })

        reader.readAsDataURL(file)
        return promise
    })

    const results = await Promise.all(promises)
    images.value.push(...results)
}

const removeImageFromGallery = (index) => {
    images.value.splice(index, 1)
    files.value.splice(index, 1)
}

const resetForm = () => {
    form.value = Object.assign({}, {
        name: '',
        description: '',
        price: '',
        stock: '',
        status: '',
        images: [],
        processing: false,
    });

    images.value = Object.assign([], [])
    files.value = Object.assign([], [])
}

const resetStatus = () => {
    status.value = Object.assign({}, {
        message: '',
        success: false,
    });
}

const submit = () => {

    form.value.processing = true

    form.value.images = files.value

    axios
        .post(`/admin/products`, form.value, {headers : {'content-type': 'multipart/form-data'}})
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

                        if(key.startsWith('images.')){
                            errors.value['image_items'] = dataErrors[key][0];
                        }else{
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

</script>

<template>
    <div class="row">

        <div class="col-md-12">

            <form @submit.prevent="submit">

                <div class="row g-3">

                    <div v-if="status.success" class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>{{ status.message }}</strong>
                    </div>

                    <div v-if="errors.server">
                        <p class="text-danger fw-bold mb-4">
                            {{ errors.server }}
                        </p>
                    </div>

                    <div class="col-12">

                        <div class="row">

                            <div class="col-sm-6 order-1 order-sm-1 d-flex align-items-center">
                                <span class="text-muted text-primary font-weight-bold">
                                    Galería de Imágenes (Máximo 5)
                                </span>
                            </div>

                            <div class="col-sm-6 order-3 order-sm-2 d-flex flex-row-reverse">
                                <div class="d-inline-block">
                                    <label for="image-upload-trigger" class="file-mask btn btn-secondary py-1 mb-0">
                                        <span class="d-inline-block" style="width:130px"><i
                                                class="ion ion-plus-round align-middle mr-1"></i> Agregar Imagen</span>
                                    </label>
                                    <input type="file" class="form-control-file d-none" accept=".png, .jpg, .jpeg"
                                        id="image-upload-trigger" multiple @change="addImagesToGallery">
                                </div>
                            </div>

                            <div class="col-12 order-2 order-sm-3 mb-5 mt-3 mb-sm-0">

                                <InputError class="mt-2" :message="errors.images" />

                                <InputError class="mt-2" :message="errors.image_items" />

                                <div id="image-upload-grid" class="image-upload-grid">
                                    <div v-for="(image, index) in images" :key="index" class="image-upload-item mx-auto">
                                        <div class="card text-center rounded-0 x-shadow-hover-bold">
                                            <div class="card-body p-0 position-relative">
                                                <img :src="image" class="img-fluid image-gallery" alt="Image"
                                                    data-toggle="tooltip" data-placement="bottom">
                                                <span class="btn-remove" tabindex="0" data-toggle="tooltip"
                                                    data-placement="left" title="Eliminar" @click="removeImageFromGallery(index)"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>

                    <div class="form-medium">

                        <div class="row g-3">

                            <div class="col-sm-6">
                                <div class="form-floating">
                                    <input type="text" id="name" class="form-control" placeholder="Nombre"
                                        v-model="form.name">
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
                                    <input type="number" id="stock" class="form-control" placeholder="Stock"
                                        v-model="form.stock">
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

                    </div>


                </div>

                <div class="form-medium">

                    <div class="row g-3">
                        <hr class="my-4">

                        <PrimaryButton class="ml-4" :disabled="form.processing">
                            Guardar
                        </PrimaryButton>

                    </div>
                </div>

            </form>

        </div>

    </div>
</template>
