<template>
    <div class="flex flex-col w-full h-screen items-center justify-center" v-if="dataLoaded">
        <question-component-vue :question="questions[currentQuestion]"></question-component-vue>
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
//import { Questions } from '@/models/questions.model';
import axios from 'axios';
import { mainStore } from '@/store/main.store';
import { storeToRefs } from 'pinia';
import logger from '@/Logger';
import LoadingComponentVue from '@/components/LoadingComponent.vue';
import QuestionComponentVue from '@/components/Questions/QuestionComponent.vue';

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

        const fetchQuestions = async () => {
            try {
                loading.value = true;
                const user = await localforage.getItem<User>(`user`);
                if(user) {
                    const response = await axios.get(`${url.value}frontend/quiz/questions?user_id=${user.id}`);
                    if(process.env.NODE_ENV === "development") console.log(response.data);
                    if(response.data.success) {
                        questions.value = response.data.questions;
                        await localforage.setItem(`quiz_id`, response.data.quiz_id);
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

        onMounted(() => {
            fetchQuestions();
        });

        return {
            loading,
            questions,
            currentQuestion,
            dataLoaded,
        }
    }
})
</script>