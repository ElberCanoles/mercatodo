<script setup>

import { onMounted, ref } from "vue";

const products = ref([])
const links = ref([])

const LENGTH_PER_PAGE = 9;

const url = ref(`/products?`)

const search = ref({
    name: '',
    minimum_price: '',
    maximum_price: '',
})

const getData = async (url) => {

    try {
        const { data } = await axios.get(`${url}&per_page=${LENGTH_PER_PAGE}&name=${search.value.name}` +
            `&minimum_price=${search.value.minimum_price}&maximum_price=${search.value.maximum_price}`)

        products.value = data.data
        links.value = data.meta.links

    } catch (error) {

    }
}

const addToCart = (url) => {
    try {

        axios
            .post(url)
            .then((response) => {
                toastr.success(response.data.message, 'Operación exitosa', {
                    timeOut: 5000
                })
            })
            .catch((exception) => {

                if (exception.response.status == 401) {
                    window.location.replace('/login')
                }else{
                    toastr.error(exception.response.data.error, 'Atención', {
                        timeOut: 5000
                    })
                }
            });

    } catch (error) {
        console.log(error)
    }
}

onMounted(() => {
    getData(url.value)
});


</script>

<template>
    <div class="album py-5 bg-light">
        <div class="container">

            <div class="row mb-4">
                <div class="col-6">
                    <div class="form-floating">
                        <input type="text" v-model="search.name" id="search" class="form-control" maxlength="100"
                            placeholder="Nombre del producto" @keyup.prevent="getData(url)">
                        <label for="search">Buscar</label>
                    </div>
                </div>

                <div class="col-3">
                    <div class="form-floating">
                        <input type="number" v-model="search.minimum_price" id="minimum_price" class="form-control"
                            placeholder="Nombre del producto" @keyup.prevent="getData(url)">
                        <label for="minimum_price">Precio mínimo</label>
                    </div>
                </div>

                <div class="col-3">
                    <div class="form-floating">
                        <input type="number" v-model="search.maximum_price" id="maximum_price" class="form-control"
                            placeholder="Nombre del producto" @keyup.prevent="getData(url)">
                        <label for="maximum_price">Precio máximo</label>
                    </div>
                </div>
            </div>

            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                <div class="col" v-for="(product, index) in products" :key="index">
                    <div class="card shadow-sm">

                        <img class="bd-placeholder-img card-img-top" width="100%" height="225"
                            :src="product?.images[0]?.path" loading="lazy" :alt="product.name">

                        <div class="card-body">
                            <p class="card-text">{{ product.name }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-outline-secondary"
                                            @click="addToCart(product.add_to_cart_url)">Al carrito</button>
                                    <a :href="product.show_url" class="btn btn-sm btn-outline-secondary">Ver</a>
                                </div>
                                <small class="text-muted">${{ product.price }}</small>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <nav aria-label="Page navigation" class="mt-4">
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
    </div>
</template>
