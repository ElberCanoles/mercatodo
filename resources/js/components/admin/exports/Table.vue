<script setup>
import { ref } from 'vue'

const exports = ref([])
const links = ref([])

const url = ref('/admin/exports?')

const getData = async (url) => {

    try {
        const { data } = await axios.get(`${url}`)

        exports.value = data.data
        links.value = data.meta.links

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
                <th scope="col">Modulo</th>
                <th scope="col">Fecha de creación</th>
                <th scope="col">Hora de creación</th>
                <th scope="col">Acciones</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(item, index) in exports" :key="index">
                <td>{{ item?.module }}</td>
                <td>{{ item?.date }}</td>
                <td>{{ item?.hour }}</td>
                <td>
                    <a :href="item.path" target="_blank" class="me-2">Descargar</a>
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
