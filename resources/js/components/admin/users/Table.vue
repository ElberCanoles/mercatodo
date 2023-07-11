<script setup>

import {ref} from 'vue'

const users = ref([])
const links = ref([])

const url = ref('/admin/users?')

const search = ref({
    name: '',
    last_name: '',
    email: ''
})

const classVerification = (value) => {

    let verifyClass = ''

    switch (value) {

        case 'user.verified':
            verifyClass = 'bg-primary'
            break;

        case 'user.non_verified':
            verifyClass = 'bg-secondary'
            break;

    }
    return verifyClass
}

const classStatus = (value) => {

    let verifyClass = ''

    switch (value) {

        case 'user.active':
            verifyClass = 'bg-success'
            break;

        case 'user.inactive':
            verifyClass = 'bg-danger'
            break;

    }
    return verifyClass
}

const getData = async (url) => {

    try {
        const {data} = await axios.get(`${url}&name=${search.value.name}&last_name=${search.value.last_name}&email=${search.value.email}`)
        users.value = data.data
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
                <th scope="col">
                    <input type="text" v-model="search.name" class="form-control" placeholder="Buscar por nombres"
                           @keyup.prevent="getData(url)">
                </th>
                <th scope="col">
                    <input type="text" v-model="search.last_name" class="form-control"
                           placeholder="Buscar por apellidos" @keyup.prevent="getData(url)">
                </th>
                <th scope="col">
                    <input type="text" v-model="search.email" class="form-control"
                           placeholder="Buscar por correo electrónico" @keyup.prevent="getData(url)">
                </th>
                <th scope="col"></th>
                <th scope="col"></th>
                <th scope="col"></th>
                <th scope="col"></th>
            </tr>
            <tr>
                <th scope="col">Nombres</th>
                <th scope="col">Apellidos</th>
                <th scope="col">Correo electrónico</th>
                <th scope="col">Verificación</th>
                <th scope="col">Estado</th>
                <th scope="col">Fecha de registro</th>
                <th scope="col">Acciones</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(user, index) in users" :key="index">
                <td>{{ user?.name }}</td>
                <td>{{ user?.last_name }}</td>
                <td>{{ user?.email }}</td>
                <td>
                    <span class="badge rounded-pill" :class="classVerification(user?.verified_key)">{{ user?.verified_value }}</span>
                </td>
                <td>
                    <span class="badge rounded-pill" :class="classStatus(user?.status_key)">{{ user?.status_value }}</span>
                </td>
                <td>{{ user?.created_at }}</td>
                <td>
                    <a :href="user.edit_url">Editar</a>
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
