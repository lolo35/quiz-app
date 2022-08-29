<template>
  <router-view/>
</template>
<script lang="ts">
import { defineComponent } from 'vue';
import { onMounted } from 'vue';
import { mainStore } from '@/store/main.store';

export default defineComponent({
  setup() {
    const store = mainStore();
    onMounted(() => {
      if(process.env.NODE_ENV === "development") {
        document.body.classList.add('debug-screens');
        store.setURL("http://localhost:8000/api/");
      }
    });
  }
});
</script>
<style>

</style>
