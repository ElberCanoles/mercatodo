<script setup>

const props = defineProps({
    product: Object,
});

</script>

<template>

    <div>
        <h1 class="mt-4">{{ product.name }}</h1>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <div class="d-flex flex-row flex-nowrap overflow-auto">
                                <div class="col-md-3 col-sm-4 col-xs-6" v-for="(image, index) in product.images" :key="index">
                                    <div class="card">
                                        <img :src="image.path" loading="lazy" class="card-img-top img-fluid d-block" style="height: 300px;width: 500px;" alt="Image">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <h2 class="mt-4">${{ product.price }}</h2>
            <span>Unidades disponibles: {{ product.stock }}</span>
            <p class="mt-4">{{ product.description }}</p>
        </div>

    </div>
</template>
