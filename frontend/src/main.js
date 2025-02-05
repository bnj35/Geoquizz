import './assets/main.css'

import { createApp } from 'vue'
import { createPinia } from 'pinia'
import piniaPersist from 'pinia-plugin-persist';

import App from './App.vue'
import router from './router'
import api from "./utils/api.js";

const app = createApp(App)

const pinia = createPinia();
pinia.use(piniaPersist)
app.use(pinia)
app.use(api, {baseUrl: import.meta.env.VITE_API_URL})
app.use(router)

app.mount('#app')
