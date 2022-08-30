import { defineStore } from 'pinia';
import { SubmitedAnswers, Answer } from '@/models/submitedAnswers.model';

export const submitedAnswers = defineStore({
    id: "submitedAnswers",
    state: ():SubmitedAnswers => {
        return {
            submited_answers: []
        }
    },
    actions: {
        addAnswer(value:Answer) {
            this.$state.submited_answers.push(value);
        },
        removeAnswer(value:number) {
            for(let i = 0; i < this.$state.submited_answers.length; i++) {
                if(value === this.$state.submited_answers[i].id) {
                    this.$state.submited_answers.splice(i);
                }
            }
        }
    },
    getters: {
        storeAnswers(state:SubmitedAnswers) {
            return state.submited_answers;
        }
    }
});