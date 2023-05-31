<script setup>

import {ref, onMounted} from "vue";
import {library} from '@fortawesome/fontawesome-svg-core'
import {faTrash, faGears} from '@fortawesome/free-solid-svg-icons'
import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome'
import Swal from "sweetalert2";

library.add(faTrash, faGears)

const products = ref([])
const total = ref(0)

const url = ref('/cart')

const getData = async (url) => {

    try {
        const {data} = await axios.get(`${url}`)

        products.value = data.products
        total.value = data.total

    } catch (error) {
        console.log(error)
    }
}

const reloadTable = async () => {
    await getData(url.value)
}

const addToCart = async (url) => {
    try {

        axios
            .post(url)
            .then((response) => {

                reloadTable()

                toastr.success(response.data.message, 'Operación exitosa', {
                    timeOut: 5000
                })
            })
            .catch((exception) => {
                toastr.error(exception.response.data.error, 'Atención', {
                    timeOut: 5000
                })
            });

    } catch (error) {
        console.log(error)
    }
}

const lessToCart = async (url) => {
    try {

        axios
            .post(url)
            .then((response) => {

                reloadTable()

                toastr.success(response.data.message, 'Operación exitosa', {
                    timeOut: 5000
                })
            })
            .catch((exception) => {
                toastr.error(exception.response.data.error, 'Atención', {
                    timeOut: 5000
                })
            });

    } catch (error) {
        console.log(error)
    }
}

const removeFromCart = async (url) => {
    try {

        Swal.fire({
            title: "¿ Esta seguro ?",
            text: "Confirme que desea remover este producto del carrito",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si, remover",
            cancelButtonText: "Cancelar",
        }).then((result) => {

            if (result.isConfirmed) {

                try {

                    axios
                        .delete(url)
                        .then((response) => {

                            Swal.fire({
                                icon: 'success',
                                title: 'Operación exitosa!',
                                text: response.data.message,
                            })

                            reloadTable()
                        })
                        .catch((exception) => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: exception.response.data.error,
                            })
                        });

                } catch (error) {
                    console.log(error)
                }

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

            <h1>Carrito de Compra</h1>

            <div v-if="products.length === 0">
                <p>Aun no ha agregado productos a su carrito.</p>
            </div>

            <div v-else>
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">
                            <font-awesome-icon icon="gears"/>
                        </th>
                        <th scope="col">Producto</th>
                        <th scope="col">Precio</th>
                        <th scope="col">Cantidad</th>
                        <th scope="col" class="text-end">Sub total</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(product, index) in products" :key="index">
                        <td>
                            <button class="btn btn-outline-danger" type="button"
                                    @click.prevent="removeFromCart(product?.remove_from_cart_url)">
                                <font-awesome-icon icon="trash"/>
                            </button>
                        </td>
                        <td>{{ product.name }}</td>
                        <td>${{ product.price }}</td>
                        <th scope="row">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <button class="btn btn-outline-secondary" type="button"
                                            @click.prevent="lessToCart(product?.less_to_cart_url)">-
                                    </button>
                                </div>
                                <span id="quantity" class="form-control mx-2">{{ product.quantity }}</span>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button"
                                            @click.prevent="addToCart(product?.add_to_cart_url)">+
                                    </button>
                                </div>
                            </div>
                        </th>
                        <th scope="row" class="text-end">${{ product.sub_total }}</th>
                    </tr>
                    </tbody>
                </table>

                <div class="text-end">
                    <h3>Total: ${{ total }}</h3>
                    <a class="btn btn-primary" href="/buyer/checkout">Continuar</a>
                </div>
            </div>

        </div>
    </div>
</template>
