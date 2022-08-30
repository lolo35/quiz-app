<template>
    <div class="fixed top-0 bottom-0 left-0 right-0 bg-black opacity-30 z-10"></div>
    <div class="fixed top-0 left-0 bottom-0 right-0 z-20">
        <div class="flex flex-col items-center justify-center w-full h-full">
            <form @submit.prevent="saveName()" class="flex flex-col px-3 py-2 bg-slate-100 rounded shadow-xl shadow-blue-900 w-2/3 space-y-2 relative">
                <loading-component v-if="loading"></loading-component>
                <div class="flex flex-row items-center px-3 py-2 justify-center">
                    <h3 class="font-semibold text-sm">Date contact</h3>
                </div>
                <div class="flex flex-col">
                    <label for="name" class="text-sm font-semibold">Nume</label>
                    <input v-model="name" type="text" class="px-3 py-2 outline-none focus:border rounded focus:border-blue-200" required>
                </div>
                <div class="flex flex-col">
                    <label for="surname" class="text-sm font-semibold">Prenume</label>
                    <input v-model="surname" type="text" class="px-3 py-2 outline-none focus:border rounded focus:border-blue-200" required>
                </div>
                <div class="flex flex-row bg-red-200 px-3 py-2 rounded items-center space-x-2" v-if="error">
                    <i class="fa-solid fa-triangle-exclamation text-red-500 animate-pulse"></i>
                    <p class="text-xs font-semibold text-red-700 text-center">Am intampinat o eroare, te rugam sa incerci mai tarziu</p>
                </div>
                <div class="flex flex-row-reverse">
                    <button type="submit" class="bg-sky-700 hover:bg-sky-600 focus:bg-sky-600 px-3 py-2 text-white rounded shadow">Salveaza</button>
                </div>
            </form>
        </div>
    </div>
</template>
<script lang="ts">
import { defineComponent, ref } from 'vue';
import localforage from 'localforage';
import { mainStore } from '@/store/main.store';
import axios from 'axios';
import { storeToRefs } from 'pinia';
import LoadingComponent from '@/components/LoadingComponent.vue';
import logger from '@/Logger';

export default defineComponent({
    components: {
        LoadingComponent,
    },
    setup() {
        const store = mainStore();
        const { url } = storeToRefs(store);
        const name = ref<string>("");
        const surname = ref<string>("");
        const loading = ref<boolean>(false);
        const error = ref<boolean>(false);
        
        const saveName = async () => {
            try {
                loading.value = true;
                const formData = new FormData();
                formData.append('name', name.value);
                formData.append('surname', surname.value);
                const response = await axios.post(`${url.value}frontend/users/user`, formData, { headers: {'Content-type': 'application/x-www-form-urlencoded'}});
                if(process.env.NODE_ENV === "development") console.log(response.data);
                if(response.data.success) {
                    await localforage.setItem(`user`, response.data.user);
                    store.setShowUserModal(false);
                }
                loading.value = false;
            } catch (exception) {
                if(process.env.NODE_ENV === "development") console.error(exception);
                if(exception instanceof Error) {
                    logger(url.value, exception.message);
                }
                loading.value = false;
            }
        }

        return {
            name,
            surname,
            saveName,
            loading,
            error,
        }
    }
});
</script>