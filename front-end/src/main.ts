import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import '@/assets/css/main.css';
import '@animxyz/core';
import VueAnimXyz from '@animxyz/vue3';
import localforage from 'localforage';
import { createPinia } from 'pinia';

localforage.config({
    name: "Quiz-app"
});

const app = createApp(App);
app.use(router);
app.use(VueAnimXyz);
app.use(createPinia());
app.mount('#app')
