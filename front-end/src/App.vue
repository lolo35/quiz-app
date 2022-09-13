<template>
  <NavbarComponentVue></NavbarComponentVue>
  <router-view/>
  <background-component-vue></background-component-vue>
</template>
<script lang="ts">
import { defineComponent } from 'vue';
import { onMounted } from 'vue';
import { mainStore } from '@/store/main.store';
import BackgroundComponentVue from './components/Modal/BackgroundComponent.vue';
import NavbarComponentVue from './components/Navigation/NavbarComponent.vue';

export default defineComponent({
  components: {
    BackgroundComponentVue,
    NavbarComponentVue,
  },
  setup() {
    const store = mainStore();
    onMounted(() => {
      store.setURL("http://18.195.236.148/api/");
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
