import './bootstrap';
import { createApp } from 'vue/dist/vue.esm-bundler';
import LoginForm from '@/Components/auth/LoginForm.vue';
import RegisterForm from '@/Components/auth/RegisterForm.vue'

window.app = createApp({
    setup() {
        return {
            message: 'Welcome to Your Vue.js App',
        };
    },
    components: {
        LoginForm,
        RegisterForm,
    },
}).mount('#app');