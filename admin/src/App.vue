<template>
  <main class="flex w-full h-screen">
    <sidebar-component-vue></sidebar-component-vue>
    <section class="w-full max-h-full overflow-y-scroll relative">
      <navbar-component></navbar-component>
      <router-view class="px-3 py-2"/>
    </section>
  </main>
  
</template>

<style>

</style>
<script lang="ts">
import { defineComponent, onMounted } from 'vue';
import { mainStore } from './store/mainStore';
import NavbarComponent from '@/components/Navigation/NavbarComponent.vue';
import SidebarComponentVue from './components/Navigation/SidebarComponent.vue';
export default defineComponent({
  components: {
    NavbarComponent,
    SidebarComponentVue,
  },
  setup() {
    const store = mainStore();
    onMounted(() => {
      store.setUrl("url");
      if(process.env.NODE_ENV === "development") {
        store.setUrl("http://localhost:8000/api/");
        document.body.classList.add(`debug-screens`);
      }
    });
  }
});
</script>