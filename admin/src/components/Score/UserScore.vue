<template>
    <div class="flex flex-col rounded px-3 py-2 bg-slate-200 dark:bg-slate-500 w-1/3 space-y-2 relative shadow-lg">
        <loading-component-vue v-if="loading"></loading-component-vue>
        <div class="flex flex-row px-3 py-2">
            <h3 class="text-lg font-bold text-gray-800 dark:text-white">{{ user.name }} {{ user.surname }}</h3>
        </div>
        <div class="flex flex-row items-center justify-between px-3 py-2">
            <p class="text-sm font-bold text-gray-700">Puncte</p>
            <p class="text-sm font-bold text-gray-700">Procentaj</p>
        </div>
        <div class="flex flex-row items-center justify-between px-3 py-2">
            <p class="text-lg font-bold text-gray-700"> {{ results.points }} / {{ results.total }}</p>
            <p class="text-lg font-bold text-gray-700" :class="{'text-red-500 animate-pulse': results.percent < results.passing }">{{ results.percent }} / {{ results.passing }}</p>
        </div>
        <div class="flex flex-row bg-red-200 px-3 py-2 justify-center rounded" v-if="results.percent < results.passing">
            <p class="text-xl font-bold animate-pulse text-center text-red-500">Necastigator</p>
        </div>
        <div class="flex flex-row bg-green-200 px-3 py-2 justify-center rounded" v-if="results.percent >= results.passing">
            <p class="text-xl font-bold animate-pulse text-green-500">Castigator</p>
        </div>
        <div class="flex flex-row" v-if="!rewardState && !rewarded && results.percent >= results.passing">
            <button @click="rewardUser()" class="w-full bg-sky-500 px-3 py-2 text-white rounded hover:bg-sky-600 dark:bg-blue-500 dark:hover:bg-blue-700">
                Premiaza
            </button>
        </div>
        <div class="flex flex-row bg-green-200 px-3 py-2 justify-center rounded" v-if="rewarded">
            <p class="text-lg font-bold animate-pulse text-green-500">Utilizatorul a fost premiat!</p>
        </div>
        <div class="flex flex-row bg-yellow-200 px-3 py-2 justify-center rounded" v-if="rewardState?.rewarded">
            <p class="text-lg font-bold animate-pulse text-yellow-500">Utilizatorul a fost deja premiat!</p>
        </div>
    </div>
</template>
<script lang="ts">
import { defineComponent, PropType, ref } from 'vue';
import { UserInterface } from '@/models/user.interface';
import { ResultInterface, RewardStateInterface } from '@/models/result.interface';
import { mainStore } from '@/store/mainStore';
import { storeToRefs } from 'pinia';
import LoadingComponentVue from '../LoadingComponent.vue';

export default defineComponent({
    components: {
        LoadingComponentVue,
    },
    props: {
        user: {
            type: Object as PropType<UserInterface>,
            required: true,
        },
        results: {
            type: Object as PropType<ResultInterface>,
            required: true,
        },
        rewardState: {
            type: Object as PropType<RewardStateInterface | null>,
            required: true,
        },
        quiz_id: {
            type: Number,
            required: true,
        }
    },
    setup(props) {
        const store = mainStore();
        const { url } = storeToRefs(store);
        const rewarded = ref<boolean>(false);
        const loading = ref<boolean>(false);

        const rewardUser = async () => {
            try {
                loading.value = true;
                const data = {
                    user_id: props.user.id,
                    quiz_id: props.quiz_id
                };

                const options = {
                    method: 'POST',
                    headers: {
                        'Content-type': 'application/json'
                    },
                    body: JSON.stringify(data)
                };

                const request = await fetch(`${url.value}admin/score/reward_user`, options);
                const response = await request.json();
                if(process.env.NODE_ENV === "development") console.log(response);
                if(response.success) {
                    rewarded.value = true;
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
            rewardUser,
            rewarded,
            loading
        }
    }
});
</script>