// import './assets/main.css'

// Importe o arquivo CSS do Tailwind
// import 'tailwindcss/tailwind.css';

import { createApp } from 'vue'
import { createPinia } from 'pinia'

import App from './App.vue'
import router from './router'
import axios from 'axios';

const app = createApp(App)

app.use(createPinia())
app.use(router)

// Configurando o Axios para usar a URL base
axios.defaults.baseURL = 'http://127.0.0.1:8000/api';

// Adicionando o Axios à instância do Vue
app.config.globalProperties.$axios = axios;

app.mount('#app')