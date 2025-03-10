import './assets/main.css'

import {createApp} from 'vue'
import {createPinia} from 'pinia'
import piniaPersist from 'pinia-plugin-persist';
import {useAPI} from "./utils/api";


import App from './App.vue'
import router from './router'

const app = createApp(App)

const pinia = createPinia();
pinia.use(piniaPersist)
app.use(pinia)
app.use(useAPI)
app.use(router)

app.mount('#app')

