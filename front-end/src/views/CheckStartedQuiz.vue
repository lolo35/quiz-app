<template>
    <div class="flex flex-col h-screen w-full items-center justify-center" v-if="quizFinished">
        <div class="flex flex-col px-3 py-2 bg-slate-100 rounded shadow-lg w-2/3 space-y-3">
            <div class="flex flex-row justify-center">
                <h3 class="font-semibold text-gray-700 text-center">Aveti un quiz finalizat</h3>
            </div>
            <div class="flex flex-row justify-center">
                <h3 class="font-bold text-center">Doriti sa incepeti altul?</h3>
            </div>
            <div class="flex flex-row items-center">
                <button class="w-full bg-green-500 hover:bg-green-600 px-3 py-2 text-white rounded-l" @click="restartQuiz()">Da</button>
                <router-link to="/" class="flex flex-row justify-center w-full bg-red-500 hover:bg-red-600 px-3 py-2 text-white rounded-r">Nu</router-link>
            </div>
        </div>
    </div>
</template>
<script lang="ts">
import { defineComponent, onMounted, ref } from 'vue';
import { useRouter } from 'vue-router';
import logger from '@/Logger';
import { mainStore } from '@/store/main.store';
import { storeToRefs } from 'pinia';
import localforage from 'localforage';
import { StartedQuizes } from '@/models/startedQuizes.models';
export default defineComponent({
    setup() {
        const store = mainStore();
        const { url } = storeToRefs(store);
        const quizFinished = ref<boolean>(false);
        const router = useRouter();

        const checkForFinishedQuiz = async () => {
            try {
                const quiz_id = await localforage.getItem<StartedQuizes>(`quiz_id`);
                if(quiz_id) {
                    if(quiz_id.finished) {
                        console.log(`this quiz is finished`);
                        quizFinished.value = true;
                        return 0;
                    }
                }
                router.push('/quiz');
            } catch (exception) {
                if(exception instanceof Error) {
                    if(process.env.NODE_ENV === "development") throw new Error(exception.message);
                    logger(url.value, exception.message);
                }
            }
        }

        const restartQuiz = async () => {
            try {
                await localforage.removeItem(`quiz_id`);
                router.push('/quiz');
            } catch (exception) {
                if(exception instanceof Error) {
                    if(process.env.NODE_ENV === "development") throw new Error(exception.message);
                    logger(url.value, exception.message);
                }
            }
        }

        onMounted(() => {
            checkForFinishedQuiz();
        });

        return {
            quizFinished,
            restartQuiz,
        }
    }
})
</script>