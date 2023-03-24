import axios from 'axios';
import './bootstrap.bundle.min.js'
import './jquery-3.6.4.min.js'

window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

