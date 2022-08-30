<template>
    <div class="border flex flex-row flex-wrap px-2 py-2 rounded items-center space-x-2" 
        :class="{'bg-green-200': clicked}"
        @click="clickAnswer()">
        <input type="radio" :checked="clicked">
        <p>{{ answer.answer }}</p>
    </div>
</template>
<script lang="ts">
import { defineComponent, PropType, ref, watch } from 'vue';
import { Answer } from '@/models/answers.model';
import { submitedAnswers } from '@/store/submitedAnswers.store';
import { mainStore } from '@/store/main.store';
import { storeToRefs } from 'pinia';


export default defineComponent({
    props: {
        answer: {
            type: Object as PropType<Answer>,
            required: true,
        }
    },
    setup(props) {
        const clicked = ref<boolean>(false);
        const submitedAnswersStore = submitedAnswers();
        const store = mainStore();
        const { currentQuestion } = storeToRefs(store);
        
        watch(currentQuestion, (newValue, oldValue) => {
            if(newValue > oldValue) {
                clicked.value = false;
            }
        });

        const clickAnswer = () => {
            clicked.value = !clicked.value;
            if(clicked.value) {
                submitedAnswersStore.addAnswer(props.answer);
            } else {
                submitedAnswersStore.removeAnswer(props.answer.id);
            }
        }

        return {
            clicked,
            clickAnswer,

        }
    }
})
</script>