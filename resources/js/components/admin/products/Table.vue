<script setup>

import { ref } from 'vue'

const props = defineProps({
    statuses: {},
});

const products = ref([])
const links = ref([])

const url = ref('/admin/products?')

const search = ref({
    name: '',
    price: '',
    stock: '',
    status: ''
})

const classStatus = (value) => {

    let verifyClass = ''

    switch (value) {

        case 'product.available':
            verifyClass = 'bg-success'
            break;

        case 'product.unavailable':
            verifyClass = 'bg-danger'
            break;

    }
    return verifyClass
}

const getData = async (url) => {

    try {
        const { data } = await axios.get(`${url}
            &name=${search.value.name}
            &price=${search.value.price}
            &stock=${search.value.stock}
            &status=${search.value.status}`)

        products.value = data.data
        links.value = data.links

    } catch (error) {

    }
}


getData(url.value)

</script>

<template>
    <div class="table-responsive">
        <table class="table table-striped table-sm">
            <thead>
                <tr>
                    <th scope="col">
                        <input type="text" v-model="search.name" class="form-control" placeholder="Buscar por nombre"
                            @keyup.prevent="getData(url)">
                    </th>
                    <th scope="col">
                        <input type="text" v-model="search.price" class="form-control" placeholder="Buscar por precio"
                            @keyup.prevent="getData(url)">
                    </th>
                    <th scope="col">
                        <input type="text" v-model="search.stock" class="form-control" placeholder="Buscar por stock"
                            @keyup.prevent="getData(url)">
                    </th>
                    <th scope="col">
                        <select class="form-control" v-model="search.status" @change.prevent="getData(url)">
                            <option value="">Todos</option>
                            <option v-for="(status, index) in statuses" :key="index" :value="status.key">{{ status.value }}
                            </option>
                        </select>
                    </th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                </tr>
                <tr>
                    <th scope="col">Nombre</th>
                    <th scope="col">Precio</th>
                    <th scope="col">Stock</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Fecha de registro</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(product, index) in products" :key="index">
                    <td>{{ product?.name }}</td>
                    <td>{{ product?.price }}</td>
                    <td>{{ product?.stock }}</td>
                    <td>
                        <span class="badge rounded-pill" :class="classStatus(product?.status_key)">{{ product?.status_value
                        }}</span>
                    </td>
                    <td>{{ product?.created_at }}</td>
                    <td>
                        <a :href="product?.edit_url">Editar</a>
                    </td>
                </tr>
            </tbody>
        </table>

        <nav aria-label="Page navigation">
            <ul class="pagination">
                <li class="page-item" :class="{ 'disabled': !link.url, 'active': link.active }"
                    v-for="(link, index) in links" :key="index">
                    <a class="page-link" v-if="link.url" href="javascript:" @click="getData(link.url)"
                        v-html="link.label"></a>
                    <span v-else v-html="link.label" class="page-link"></span>
                </li>
            </ul>
        </nav>
    </div>
</template>
