import { createApp } from 'vue';
import { createPinia } from 'pinia';
import piniaPersist from 'pinia-plugin-persist';

import App from './App.vue';
import router from './router';
import api from "./utils/api.js";

import './assets/main.css';

const baseUrl = import.meta.env.VITE_API_BASE_URL;

if (!baseUrl) {
  console.error("⚠️ Erreur : VITE_API_BASE_URL n'est pas défini dans .env !");
}

const app = createApp(App);

const pinia = createPinia();
pinia.use(piniaPersist);
app.use(pinia);
app.use(api, { baseUrl }); // ✅ Passer baseUrl correctement
app.use(router);

app.mount('#app');
