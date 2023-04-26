<script setup>

import {onMounted, ref} from "vue";

const products = ref([])
const links = ref([])

const LENGTH_PER_PAGE = 9;

const url = ref(`/buyer/products?`)

const getData = async (url) => {

    try {
        const { data } = await axios.get(`${url}&per_page=${LENGTH_PER_PAGE}`)
        products.value = data.data
        links.value = data.links

    } catch (error) {

    }
}

onMounted(() => {
    getData(url.value)
});


</script>

<template>
    <div class="album py-5 bg-light">
        <div class="container">

            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                <div class="col" v-for="(product, index) in products" :key="index">
                    <div class="card shadow-sm">

                        <img class="bd-placeholder-img card-img-top" width="100%" height="225" :src="product.images[0].path" loading="lazy" :alt="product.name">

                        <div class="card-body">
                            <p class="card-text">{{ product.name }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-outline-secondary">Al carrito</button>
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
