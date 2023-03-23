import './bootstrap';
import { createApp } from 'vue/dist/vue.esm-bundler';

window.app = createApp({
    setup() {
        return {
            message: 'Welcome to Your Vue.js App',
        };
    },
    components: {
        
    },
}).mount('#app');