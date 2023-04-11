import './bootstrap';
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

import BuyerProfileEditForm from '@/components/buyer/profile/EditForm.vue'


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
        AdminProfileEditForm,
        BuyerProfileEditForm,
    },
}).mount('#app');
