<script setup>

import {ref} from "vue";
import InputError from '@/components/common/InputError.vue'

const form = ref({
    file: File,
    processing: false,
})

const errors = ref({})

const resetForm = () => {
    form.value = Object.assign({}, {
        file: File,
        processing: false,
    });

    const importForm = document.getElementById("importForm")
    const closeModal = document.getElementById("closeModal")
    importForm.reset()
    closeModal.click()
}

const attachFile = async (e) => {
    form.value.file = e.target.files[0]
}

const submit = () => {

    form.value.processing = true

    axios
        .post(`/admin/products/import`, form.value, {headers: {'content-type': 'multipart/form-data'}})
        .then((response) => {
            errors.value = {}
            resetForm()

            toastr.success(response.data.message, 'Operación exitosa', {
                timeOut: 5000
            })
        })
        .catch((exception) => {

            errors.value = {}

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

</script>

<template>
    <!-- Modal -->
    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form @submit.prevent="submit" id="importForm">
                    <div class="modal-header">
                        <h5 class="modal-title">Importar Productos</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="file" id="file" @change="attachFile">
                        <InputError class="mt-2" :message="errors.file"/>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="closeModal">Cerrar</button>
                        <button type="submit" class="btn btn-secondary" style="background: #003D46" :disabled="form.processing">
                            Importar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
