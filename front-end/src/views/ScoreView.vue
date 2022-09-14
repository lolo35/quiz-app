<template>
    <div class="flex flex-col w-full h-screen items-center justify-center">
        <div class="flex flex-col bg-slate-300 px-3 py-2 rounded w-2/3 items-center justify-center" 
            :class="{'win-background': max >= passingGrade && !loading, 'loose-background': max < passingGrade && !loading}">
            <div class="flex flex-row items-start w-full">
                <p class="text-xs font-bold">#{{ user?.id }}</p>
            </div>
            <div class="rings flex flex-col w-full h-full items-center justify-center relative">
                <div class="percent1 flex flex-col w-full h-full items-center justify-center">
                    <svg viewBox="-30 -10 200 40" preserveAspectRatio="xMaxYMax meet">
                        <circle class="stroke-slate-200"  cx="70" cy="70" r="70"></circle>
                        <circle id="exterior-circle" class="stroke-autblue" cx="70" cy="70" r="70"></circle>
                    </svg>
                </div>
                <!-- <div class="absolute top-0 left-0 right-0 bottom-0">
                    <div class="flex flex-col w-full h-full items-center justify-center">
                        <h2 class="text-3xl font-bold">
                            {{ animatedMax }}
                            <span>%</span>
                        </h2>
                    </div>
                </div> -->
            </div>
            <div class="flex flex-col w-full h-full items-center justify-center">
                <h2 class="text-3xl font-bold">
                    {{ animatedMax }}
                    <span>%</span>
                </h2>
            </div>
            <div class="flex flex-col" v-if="max >= passingGrade && !loading">
                <div class="flex flex-row bg-green-300 px-3 py-2 rounded w-full justify-center">
                    <h3 class="text-lg font-bold text-green-800 animate-pulse">Felicitari! Ai castigat!!</h3>
                </div>
                <div class="flex flex-row items-center justify-center">
                    <h3 class="font-bold text-sm">
                        {{ quizData?.win_text }}
                    </h3>
                </div>
            </div>
            <div class="flex flex-col space-y-2 w-full" v-if="max < passingGrade && !loading">
                <div class="flex flex-row bg-red-200 px-3 py-2 rounded w-full justify-center">
                    <h3 class="text-lg font-bold text-red-500">
                        {{ quizData?.loose_text }}
                    </h3>
                </div>
                <button @click="startAgain()" v-if="quizData?.repeatable" class="bg-blue-500 px-3 py-2 text-white rounded text-xs w-full">
                    Incearca-ti norocul din nou!
                </button>
            </div>
        </div>
    </div>
    <canvas width="400" height="400" id="myCanvas" class="absolute -z-10"></canvas>
</template>
<script lang="ts">
import { defineComponent, onMounted, ref } from 'vue';
import logger from '@/Logger';
import { mainStore } from '@/store/main.store';
import { storeToRefs } from 'pinia';
import localforage from 'localforage';
import { QuizID, QuizResult } from '@/models/quizID.model';
import axios from 'axios';
import party from 'party-js';
import { useRouter } from 'vue-router';
import { User } from '@/models/user.model';
import { QuizDataInterface } from '@/models/quizData.model';

export default defineComponent({
    setup () {
        const store = mainStore();
        const { url } = storeToRefs(store);
        const max = ref<number>(0);
        const animatedMax = ref<number>(0);
        const passingGrade = ref<number>(0);
        const router = useRouter();
        const showCircle = ref<boolean>(false);
        const user = ref<User>();
        const loading = ref<boolean>(true);
        const quizData = ref<QuizDataInterface | null>(null);

        const fetchUser = async () => {
            try {
                const userQuery = await localforage.getItem<User>(`user`);
                const quiz_data = await localforage.getItem<QuizDataInterface>(`quiz_data`);
                if(quiz_data) {
                    quizData.value = quiz_data;
                }

                if(userQuery) {
                    user.value = userQuery;
                }
            } catch (exception) {
                if(exception instanceof Error) {
                    if(process.env.NODE_ENV === "development") throw new Error(exception.message);
                    logger(url.value, exception.message);
                }
            }
        }

        const animateCircle = () => {
            showCircle.value = true;
            let dashOffset = 0;            
            const circle = document.querySelector<HTMLElement>(`#exterior-circle`);
            const interval = setInterval(() => {
                //console.log(circle);
                
                if(circle) {
                    const offset = 440 - (440 * dashOffset) / 100;
                    //console.log(offset);
                    circle.style.strokeDashoffset = offset as unknown as string;
                }
                if(dashOffset == max.value) {
                    clearInterval(interval);
                } else {
                    animatedMax.value++;
                    dashOffset++;
                }
            }, 30);
        }

        const startAgain = async () => {
            try {
                await localforage.removeItem(`quiz_id`);
                const tempUrl = url.value;
                store.$reset();
                url.value = tempUrl;
                router.push('/quiz');
            } catch (exception) {
                if(exception instanceof Error) {
                    logger(url.value, exception.message);
                    if(process.env.NODE_ENV === "development") throw new Error(exception.message);
                }
            }
        }

        const fetchPoints = async () => {
            try {
                loading.value = true;
                const quizData = await localforage.getItem<QuizID>(`quiz_id`);
                if(quizData) {
                    const response = await axios.get<QuizResult>(`${url.value}frontend/quiz/result?started_quiz_id=${quizData.id}&quiz_id=${quizData.quiz_id}`);
                    if(process.env.NODE_ENV === "development") console.log(response.data);
                    if(response.data.success) {
                        max.value = response.data.percent;
                        passingGrade.value = response.data.passing;
                        animateCircle();             
                        if(max.value >= passingGrade.value) {
                            
                            let myCanvas = document.querySelector<HTMLElement>("#myCanvas");
                            if(myCanvas) {
                                party.confetti(myCanvas, {
                                    count: party.variation.range(20, 40)
                                });
                            }
                        }
                    }
                }
            } catch (exception) {
                if(exception instanceof Error) {
                    if(process.env.NODE_ENV === "development") throw new Error(exception.message);
                    logger(url.value, exception.message);
                }
            } finally {
                loading.value = false;
            }
        }

        onMounted(() => {
            fetchUser(),
            fetchPoints();
        });

        return {
            max,
            animatedMax,
            passingGrade,
            startAgain,
            user,
            loading,
            quizData
        }
    }
})
</script>
<style scoped>
.win-background {
    background: url('@/assets/undraw_product_hunt_n-3-f5.svg');
    background-color: #e5ebf3;
    background-repeat: no-repeat;
    background-size: 200px 150px;
    background-position: center center;
}
.loose-background {
    background: url('@/assets/undraw_feeling_blue_-4-b7q.svg');
    background-color: #e5ebf3;
    background-repeat: no-repeat;
    background-size: 150px 150px;
    background-position: center center;
}
.percent1, svg, circle {
    width: 200px;
    height: 200px;
}
circle {
    fill: none;
    stroke-width: 10;
    transform: translate(10px, 10px);
    transform: rotate(-90deg);
    stroke-dasharray: 440;
    stroke-linecap: round;
}

.percent1 circle:nth-child(2){
    /* stroke-dashoffset: calc(440 - (440 * 75) / 100); */
    animation: percent 1.5s linear;
    /* animation-delay: 1s; */
}

@keyframes percent {
    0% {
        stroke-dashoffset: 0;
        stroke-width: 0;
    }
}
</style>