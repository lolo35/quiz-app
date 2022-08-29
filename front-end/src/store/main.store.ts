import { defineStore } from 'pinia';
import { MainStore } from '@/models/mainStore.model';

export const mainStore = defineStore({
    id: "mainStore",
    state: ():MainStore => {
        return {
            showUserModal: false,
            url: "",
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
        }
    }
});