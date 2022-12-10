import { defineStore } from "pinia";

export const useAuthStore = defineStore('auth', {
    state: () => {
        return {
            loggedIn: false,
            user: null
        }
    }
})
