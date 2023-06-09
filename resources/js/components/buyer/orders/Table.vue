<script setup>

import { ref } from 'vue'

const orders = ref([])
const links = ref([])

const url = ref('/buyer/orders?')


const classStatus = (value) => {

    let verifyClass = ''

    switch (value) {

        case 'order.confirmed':
            verifyClass = 'bg-success'
            break;

        case 'order.pending':
            verifyClass = 'bg-warning'
            break;

        case 'order.cancelled':
            verifyClass = 'bg-danger'
            break;

    }
    return verifyClass
}

const getData = async (url) => {

    try {
        const { data } = await axios.get(`${url}`)
        orders.value = data.orders.data
        links.value = data.orders.links
    } catch (error) {
        console.log(error)
    }
}


getData(url.value)

</script>

<template>
    <div class="table-responsive">
        <table class="table table-striped table-sm">
            <thead>
                <tr>
                    <th scope="col"># Orden</th>
                    <th scope="col">Importe</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Fecha de registro</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(order, index) in orders" :key="index">
                    <td>{{ order?.id }}</td>
                    <td>${{ order?.amount }}</td>
                    <td>
                        <span class="badge rounded-pill" :class="classStatus(order?.status_key)">{{ order?.status_value
                        }}</span>
                    </td>
                    <td>{{ order?.created_at }}</td>
                    <td>
                        <a :href="order.show_url">Ver Detalles</a>
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
