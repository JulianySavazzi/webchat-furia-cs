import './bootstrap';
import App from './App.vue'
import router from './router'

const app = window.VueApp

app.component('App', App)
app.use(router)

app.mount('#app')
