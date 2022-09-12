import { defineStore } from "pinia";
import { mainStoreInterface } from "@/models/mainStore.interface";

export const mainStore = defineStore({
    id: "mainStore",
    state: ():mainStoreInterface => {
        return {
            url: "",
        }
    },
    actions: {
        setUrl(value:string) {
            this.$state.url = value;
        }
    }
})