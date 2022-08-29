<template>
    <div class="fixed top-0 bottom-0 left-0 right-0 bg-black opacity-30 z-10"></div>
    <div class="fixed top-0 left-0 bottom-0 right-0 z-20">
        <div class="flex flex-col items-center justify-center w-full h-full">
            <form @submit.prevent="saveName()" class="flex flex-col px-3 py-2 bg-slate-100 rounded shadow-xl shadow-blue-900 w-2/3 space-y-2">
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

export default defineComponent({
    setup() {
        const store = mainStore();
        const name = ref<string>("");
        const surname = ref<string>("");
        
        const saveName = async () => {
            try {
                await localforage.setItem(`name`, name.value);
                await localforage.setItem(`surname`, surname.value);
                store.setShowUserModal(false);
            } catch (exception) {
                if(process.env.NODE_ENV === "development") console.error(exception);
            }
        }

        return {
            name,
            surname,
            saveName,
        }
    }
});
</script>