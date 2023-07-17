import '@/bootstrap';

import { createApp } from 'vue/dist/vue.esm-bundler';

import LoginForm from '@/components/auth/LoginForm.vue';
import RegisterForm from '@/components/auth/RegisterForm.vue'
import VerifyEmailForm from '@/components/auth/VerifyEmailForm.vue'
import ForgotPasswordForm from '@/components/auth/ForgotPasswordForm.vue'
import ResetPasswordForm from '@/components/auth/ResetPasswordForm.vue'

import AdminUsersTable from '@/components/admin/users/Table.vue'
import AdminUsersEditForm from '@/components/admin/users/crud/EditForm.vue'
import AdminProfileEditForm from '@/components/admin/profile/EditForm.vue'

import AdminProductsTable from '@/components/admin/products/Table.vue'
import AdminProductsCreateForm from '@/components/admin/products/crud/CreateForm.vue'
import AdminProductsEditForm from '@/components/admin/products/crud/EditForm.vue'

import AdminExportsTable from '@/components/admin/exports/Table.vue'

import AdminImportsTable from '@/components/admin/imports/Table.vue'

import BuyerProfileEditForm from '@/components/buyer/profile/EditForm.vue'

import BuyerCart from '@/components/buyer/cart/Cart.vue'

import BuyerCheckoutForm from '@/components/buyer/checkout/Form.vue'

import BuyerOrdersTable from '@/components/buyer/orders/Table.vue'

import GuestProductsGallery from '@/components/guest/products/Gallery.vue'
import GuestProductsShow from '@/components/guest/products/Show.vue'


window.app = createApp({
    setup() {
        return {
            message: 'Welcome to Your Vue.js App',
        };
    },
    components: {
        LoginForm,
        RegisterForm,
        VerifyEmailForm,
        ForgotPasswordForm,
        ResetPasswordForm,
        AdminUsersTable,
        AdminUsersEditForm,
        AdminProductsTable,
        AdminProductsCreateForm,
        AdminProductsEditForm,
        AdminProfileEditForm,
        AdminExportsTable,
        AdminImportsTable,
        BuyerProfileEditForm,
        BuyerCart,
        BuyerOrdersTable,
        BuyerCheckoutForm,
        GuestProductsGallery,
        GuestProductsShow
    },
}).mount('#app');
