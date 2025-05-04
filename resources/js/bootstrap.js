import axios from 'axios';
import { createApp } from 'vue'
import { createPinia } from 'pinia'

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.withCredentials = true

/**
 * Inicializacao do VUE
 *
 */
const app = createApp({})
app.use(createPinia())
window.VueApp = app

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allow your team to quickly build robust real-time web applications.
 */

import './echo';
