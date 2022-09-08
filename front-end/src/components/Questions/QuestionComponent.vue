<template>
    <div class="flex flex-col w-5/6 bg-slate-100 rounded px-3 py-2 shadow-lg relative">
        <loading-component-vue v-if="loading"></loading-component-vue>
        <div class="flex flex-row">
            <h3 class="text-lg font-bold">Intrebarea #{{currentQuestion + 1}}</h3>
        </div>
        <progress-bar-component-vue v-if="currentQuestion + 1 < questions.length" :total-questions="questions.length" :current-question="currentQuestion"></progress-bar-component-vue>
        <div class="flex flex-row px-2" v-if="currentQuestion < questions.length">
            <p class="font-semibold text-gray-500 italic">{{ question.question }}</p>
        </div>
        <div class="flex flex-col px-2 space-y-2 mt-3" v-if="showAnswers">
            <!-- <div v-for="answer in answers" :key="answer.id" >
                {{ answer.answer }}
            </div> -->

            <answer-component-vue v-for="answer in answers" :key="answer.id" :answer="answer"></answer-component-vue>
            <button v-if="submited_answers.length > 0" @click="nextQuestion()" class="bg-sky-500 px-3 py-2 rounded text-white hover:bg-sky-600 focus:bg-sky-600">
                <span v-if="currentQuestion + 1 < questions.length">Urmatoarea intrebare</span>
                <span v-else>Arata-mi scorul</span>
            </button>
        </div>
    </div>
</template>
<script lang="ts">
/**Vue stuff */
import { defineComponent, PropType, onMounted, ref, watch } from 'vue';
import { useRouter }  from 'vue-router';
/** models/interfaces */
import { Questions } from '@/models/questions.model';
import { Answer } from '@/models/answers.model';
import { User } from '@/models/user.model';
import { StartedQuizes } from '@/models/startedQuizes.models';
/**logger */
import logger from '@/Logger';
/**store stuff */
import { mainStore } from '@/store/main.store';
import { submitedAnswers } from '@/store/submitedAnswers.store';
import { storeToRefs } from 'pinia';
/**components */
import LoadingComponentVue from '../LoadingComponent.vue';
import AnswerComponentVue from './AnswerComponent.vue';
import ProgressBarComponentVue from './ProgressBarComponent.vue';
/**third-party libraries */
import localforage from 'localforage';
import axios from 'axios';

export default defineComponent({
    props: {
        question: {
            type: Object as PropType<Questions>,
            required: true,
        }
    },
    components: {
        LoadingComponentVue,
        AnswerComponentVue,
        ProgressBarComponentVue,
    },
    setup(props) {
        const store = mainStore();
        const submitedAnswersStore = submitedAnswers();
        const { url, currentQuestion, questions } = storeToRefs(store);
        const answers = ref<Answer[]>([]);
        const loading = ref<boolean>(false);
        const { submited_answers } = storeToRefs(submitedAnswersStore);
        const router = useRouter();
        const showAnswers = ref<boolean>(false);

        watch(() => props.question, () => {
            if(currentQuestion.value < questions.value.length) {
                fetchAnswers();
            }
        });

        const fetchAnswers = async () => {
            try {
                loading.value = true;
                showAnswers.value = false;
                const response = await axios.get(`${url.value}frontend/quiz/answers?question_id=${props.question.id}`);
                if(process.env.NODE_ENV === "development") console.log(response.data);
                if(response.data.success) {
                    answers.value = response.data.answers;
                    showAnswers.value = true;
                }
                loading.value = false;
            } catch (exception) {
                if(exception instanceof Error) {
                    if(process.env.NODE_ENV === "development") {
                        throw new Error(exception.message);
                    }
                    logger(url.value, exception.message);
                }
                loading.value = false;
            }
        }

        const submitAnswer = async (user:User, quiz_id:StartedQuizes) => {
            try {
                const formData = new FormData();
                formData.append('user_id', user.id as unknown as string);
                formData.append('question_id', props.question.id as unknown as string);
                formData.append('quiz_id', quiz_id?.id as unknown as string);
                const answers = JSON.stringify(submited_answers.value);
                formData.append('answers', answers);
                //console.log(answers)
                const response = await axios.post(`${url.value}frontend/quiz/answer`, formData, { headers: {"Content-type": "application/x-www-form-urlencoded"}});
                if(process.env.NODE_ENV === "development") console.log(response.data);
                if(response.data.success) {
                    store.incrementCurrentQuestion();
                    submitedAnswersStore.$reset();
                }
            } catch (exception) {
                if(exception instanceof Error) {
                    logger(url.value, exception.message);
                    if(process.env.NODE_ENV === "development") throw new Error(exception.message);
                }
            }
        }

        const nextQuestion = async () => {
            try {
                const user = await localforage.getItem<User>('user');
                const quiz_id = await localforage.getItem<StartedQuizes>(`quiz_id`);
                if(currentQuestion.value < questions.value.length - 1) {
                    
                    if(user && quiz_id) {
                        submitAnswer(user, quiz_id);
                    }
                } else {
                    //console.log(`mark quiz as finished`);
                    if(quiz_id && user) {
                        submitAnswer(user, quiz_id);
                        const formData = new FormData();
                        formData.append(`quiz_id`, quiz_id.id as unknown as string);
                        formData.append(`user_id`, user?.id as unknown as string);

                        const response = await axios.post(`${url.value}frontend/quiz/finish`, formData, { headers: {"Content-type": "application/x-www-form-urlencoded"}});
                        if(process.env.NODE_ENV === "development") console.log(`quiz finish`, response.data);
                        if(response.data.success) {
                            router.push('/score')
                        }
                        if(response.data.success) {
                            await localforage.setItem(`quiz_id`, response.data.data);
                        }
                    }
                }
            } catch (exception) {
                if(exception instanceof Error) {
                    if(process.env.NODE_ENV === "development") throw new Error(exception.message);
                    logger(url.value, exception.message);
                }
            }
        }

        onMounted(() => {
            fetchAnswers();
        });

        return {
            answers,
            loading,
            submited_answers,
            currentQuestion,
            nextQuestion,
            questions,
            showAnswers,
        }
    }
});
</script>