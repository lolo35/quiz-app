import { defineStore } from 'pinia';
import { MainStore } from '@/models/mainStore.model';

export const mainStore = defineStore({
    id: "mainStore",
    state: ():MainStore => {
        return {
            showUserModal: false,
            url: "",
            currentQuestion: 0,
            questions: []
        }
    },
    actions: {
        toggleShowUserModal() {
            this.$state.showUserModal = !this.$state.showUserModal;
        },
        setShowUserModal(value: boolean) {
            this.$state.showUserModal = value;
        },
        setURL(value: string){
            this.$state.url = value;
        },
        incrementCurrentQuestion() {
            this.$state.currentQuestion++;
        }
    }
});