import './bootstrap';
import { createApp } from 'vue/dist/vue.esm-bundler';
import LoginForm from '@/components/auth/LoginForm.vue';
import RegisterForm from '@/components/auth/RegisterForm.vue'
import VerifyEmailForm from '@/components/auth/VerifyEmailForm.vue'
import ForgotPasswordForm from '@/components/auth/ForgotPasswordForm.vue'
import ResetPasswordForm from '@/components/auth/ResetPasswordForm.vue'
import AdminUsersTable from '@/components/admin/users/Table.vue'

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
    },
}).mount('#app');
