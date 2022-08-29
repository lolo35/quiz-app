<template>
  <div class="grid grid-cols-1 w-full">
    <div class="flex flex-row w-full justify-center">
      Welcome to Autoliv quiz
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
        const username = await localforage.getItem(`name`);
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
