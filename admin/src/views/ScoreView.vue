<template>
    <div class="flex flex-col w-full h-full items-center justify-center space-y-3">
        <div class="flex flex-col w-1/3 bg-slate-200 dark:bg-slate-500 rounded relative shadow-lg">
            <loading-component-vue v-if="loading"></loading-component-vue>
            <div class="flex flex-row px-3 py-2 rounded shadow-lg border-slate-300 dark:border-slate-700 bg-gradient-to-r dark:from-slate-600 dark:to-slate-700 from-slate-300 to-slate-400">
                <h3 class="text-gray-700 font-bold dark:text-slate-200">Cautare utilizator</h3>
            </div>
            <form @submit.prevent="fetchUserResult()" class="flex flex-col w-full items-center justify-center px-3 py-2 space-y-2">
                <label for="userid" class="text-lg font-bold dark:text-white">ID Utilizator</label>
                <input v-model="userid" type="text" class="rounded px-3 py-2 w-full outline-sky-200 font-bold dark:outline-blue-500" required>
                <button type="submit" class="bg-sky-500 text-white w-full px-3 py-2 rounded hover:bg-sky-600 dark:bg-blue-500 dark:hover:bg-blue-700">Cautare</button>
            </form>
        </div>
        <user-score-vue :user="user" :results="result" :reward-state="rewardState" :quiz_id="quiz_id" v-if="dataLoaded"></user-score-vue>
    </div>
</template>
<script lang="ts">
import { defineComponent, ref } from 'vue';
import { mainStore } from '@/store/mainStore';
import { storeToRefs } from 'pinia';
import LoadingComponentVue from '@/components/LoadingComponent.vue';
import UserScoreVue from '@/components/Score/UserScore.vue';
import { UserInterface } from '@/models/user.interface';
import { ResultInterface, RewardStateInterface } from '@/models/result.interface';

export default defineComponent({
    components: {
        LoadingComponentVue,
        UserScoreVue,
    },
    setup() {
        const userid = ref("");
        const store = mainStore();
        const { url } = storeToRefs(store);
        const loading = ref<boolean>(false);
        const user = ref<UserInterface>({
            id: 0,
            name: "",
            surname: "",
            created_at: "",
            updated_at: ""
        });
        
        const result = ref<ResultInterface>({
            success: false,
            passing: 0,
            percent: 0,
            points: 0,
            total: 0,
        });

        const rewardState = ref<RewardStateInterface | null>(null);
        const quiz_id = ref<number>(0);


        const dataLoaded = ref<boolean>(false);

        const fetchUserResult = async () => {
            try {
                loading.value = true;
                dataLoaded.value = false;
                const request = await fetch(`${url.value}admin/score/result?userid=${userid.value}`);
                const response = await request.json();
                if(process.env.NODE_ENV === "development") console.log(response);
                if(response.success) {
                    user.value = response.user;
                    dataLoaded.value = true;
                    result.value = response.result;
                    quiz_id.value = response.quiz_id;
                    if(response.reward_state) {
                        rewardState.value = response.reward_state;
                    }
                }
            } catch (exception) {
                if(exception instanceof Error) {
                    if(process.env.NODE_ENV === "development") throw new Error(exception.message);
                }
            } finally {
                loading.value = false;
            }
        }

        return {
            userid,
            fetchUserResult,
            loading,
            user,
            dataLoaded,
            result,
            rewardState,
            quiz_id
        }
    }
})
</script>