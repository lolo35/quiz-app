<template>
    <div class="flex flex-col w-full h-screen items-center justify-center relative" v-if="dataLoaded">
        <question-component-vue :question="questions[currentQuestion]"></question-component-vue>
        <div class="absolute bottom-2 right-2">
            <div class="flex flex-col items-end w-full justify-end px-3 py-2 mt-3">
                <button class="bg-red-400 text-white px-3 py-1 rounded" @click="cancelQuiz()">
                    <i class="fa-solid fa-ban"></i>
                    Anuleaza
                </button>
            </div>
        </div>
    </div>
    <div class="fixed top-0 bottom-0 left-0 right-0" v-if="loading">
        <div class="flex flex-col w-full h-full relative">
            <LoadingComponentVue></LoadingComponentVue>
        </div>
    </div>
</template>
<script lang="ts">
import { defineComponent, onMounted, ref } from 'vue';
import localforage from 'localforage';
import { User } from '@/models/user.model';
import { StartedQuizes } from '@/models/startedQuizes.models';
//import { Questions } from '@/models/questions.model';
import axios from 'axios';
import { mainStore } from '@/store/main.store';
import { storeToRefs } from 'pinia';
import logger from '@/Logger';
import LoadingComponentVue from '@/components/LoadingComponent.vue';
import QuestionComponentVue from '@/components/Questions/QuestionComponent.vue';
import { useRouter } from 'vue-router';
import { QuizID } from '@/models/quizID.model';

export default defineComponent({
    components: {
        LoadingComponentVue,
        QuestionComponentVue,
    },
    setup() {
        const store = mainStore();
        const { url, currentQuestion, questions } = storeToRefs(store);
        const loading = ref<boolean>(false);
        //const questions = ref<Questions[]>([]);
        //const currentQuestion = ref<number>(0);
        const dataLoaded = ref<boolean>(false);
        const router = useRouter();

        const checkForComplete = async () => {
            try {
                const quiz = await localforage.getItem<QuizID>(`quiz_id`);
                if(quiz) {
                    if(quiz.finished) {
                        router.push('/score');
                        return;
                    }
                    fetchQuestions();
                }
            } catch (exception) {
                if(exception instanceof Error) {
                    if(process.env.NODE_ENV === "development") throw new Error(exception.message);
                }
            }
        }

        const fetchQuestions = async () => {
            try {
                loading.value = true;
                const user = await localforage.getItem<User>(`user`);
                if(user) {
                    const quiz_id = await localforage.getItem<StartedQuizes>(`quiz_id`);
                    let params:string;
                    if(quiz_id) {
                        params = `?user_id=${user.id}&quiz_id=${quiz_id.id}`
                    } else {
                        params = `?user_id=${user.id}`;
                    }

                    const response = await axios.get(`${url.value}frontend/quiz/questions${params}`);
                    if(process.env.NODE_ENV === "development") console.log(response.data);
                    if(response.data.success) {
                        questions.value = response.data.questions;
                        if(!quiz_id) {
                            await localforage.setItem(`quiz_id`, response.data.quiz_id);
                            await localforage.setItem(`quiz_data`, response.data.quiz_data);
                        }
                        dataLoaded.value = true;
                    }
                }
                loading.value = false;
            } catch (exception) {
                if(process.env.NODE_ENV === "development") console.error(exception);
                if(exception instanceof Error) {
                    logger(url.value, exception.message);
                }
                loading.value = false;
            }
        }

        const cancelQuiz = async () => {
            try {
                await localforage.removeItem(`quiz_id`);
                router.push('/');
            } catch (exception) {
                if(exception instanceof Error) {
                    if(process.env.NODE_ENV === "development") throw new Error(exception.message);
                    logger(url.value, exception.message);
                }
            }
        }

        onMounted(() => {
            checkForComplete();
        });

        return {
            loading,
            questions,
            currentQuestion,
            dataLoaded,
            cancelQuiz,
        }
    }
})
</script>