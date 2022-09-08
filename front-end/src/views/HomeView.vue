<template>
  <!-- <div class="grid grid-cols-1 w-full mt-20">
    
  </div> -->
  <div class="fixed top-0 bottom-0 left-0 right-0">
    <div class="flex flex-col w-full h-full items-center justify-center space-y-5">
      <router-link to="/check" class="bg-sky-500 px-3 py-2 text-white rounded shadow-lg w-2/3 hover:bg-sky-600 focus:bg-sky-600 flex flex-row justify-center items-center space-x-2">
        <i class="fa-solid fa-play"></i>
        <span>Start Quiz</span>
      </router-link>
      <router-link to="/score" class="bg-yellow-500 text-white px-3 py-2 rounded shadow-lg w-2/3 hover:bg-yellow-600 focus:bg-yellow-600 text-center">
        <i class="fa-solid fa-flag-checkered"></i>
        Verifica rezultatele
      </router-link>
    </div>
  </div>
  <user-modal-vue v-if="showUserModal"></user-modal-vue>
</template>

<script lang="ts">
import { defineComponent, onMounted } from 'vue';
import localforage from 'localforage';
import UserModalVue from '@/components/Modal/UserModal.vue';
import { mainStore } from '@/store/main.store';
import { storeToRefs } from 'pinia';
import logger from '@/Logger';

export default defineComponent({
  name: 'HomeView',
  setup() {
    const store = mainStore();
    const { showUserModal, url } = storeToRefs(store);

    onMounted(() => {
      checkUsername();
    });

    const checkUsername = async () => {
      try {
        const username = await localforage.getItem(`user`);
        if(!username) {
          store.setShowUserModal(true);
        }
      } catch (exception) {
        if(process.env.NODE_ENV === "development") console.error(exception);
        if(exception instanceof Error) {
          logger(url.value, exception.message);
        }
      }
    }

    return {
      showUserModal,
    }
  },
  components: {
    UserModalVue,
  },
});
</script>
