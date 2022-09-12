<template>
    <div class="flex flex-col w-20 
        dark:from-slate-700 dark:to-slate-900 
        from-autblue to-blue-700
        shadow-md bg-gradient-to-b space-y-3 justify-between
    ">
        <div class="flex flex-col space-y-10">
            <div class=" h-14 w-full z-20">&nbsp;</div>
            <div class="flex flex-row items-center justify-center">
                <router-link to="/" title="Home" :class="activeRoute === 'home' ? routeActiveClass : routeInactiveClass">
                    <i class="fa-solid fa-house fa-2x"></i>
                </router-link>
            </div>
            <div class="flex flex-row items-center justify-center">
                <router-link to="/user_score" title="Score" :class="activeRoute === 'Score' ? routeActiveClass : routeInactiveClass">
                    <i class="fa-solid fa-ranking-star fa-2x"></i>
                </router-link>
            </div>
        </div>
    </div>
</template>
<script lang="ts">
import { defineComponent, computed } from 'vue';
import { useRoute } from 'vue-router';
export default defineComponent({
    setup() {
        const route = useRoute();
        const routeActiveClass = "text-sky-500 hover:text-sky-300 dark:text-autBlue dark:hover:text-blue-700";
        const routeInactiveClass = "text-slate-300 hover:text-slate-100 dark:text-slate-200 dark:hover:text-slate-300";


        const activeRoute = computed(() => {
            return route.name;
        });

        return {
            activeRoute,
            routeActiveClass,
            routeInactiveClass,
        }
    }
});
</script>