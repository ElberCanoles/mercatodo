import './bootstrap';
import { createApp } from 'vue/dist/vue.esm-bundler';
import LoginForm from '@/components/auth/LoginForm.vue';
import RegisterForm from '@/components/auth/RegisterForm.vue'
import VerifyEmailForm from '@/components/auth/VerifyEmailForm.vue'
import ForgotPasswordForm from '@/components/auth/ForgotPasswordForm.vue'

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
    },
}).mount('#app');