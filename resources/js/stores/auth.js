import { defineStore } from "pinia";

export const useAuthStore = defineStore('auth', {
    state: () => {
        return {
            loggedIn: false,
            user: null
        }
    },

    actions: {
        logout() {
            this.loggedIn = false
            this.user = null
        }
    }
})
