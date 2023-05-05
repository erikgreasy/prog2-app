import { defineStore } from "pinia";

export const useDarkModeStore = defineStore('darkMode', {
    state: () => {
        return {
            darkMode: JSON.parse(localStorage.getItem('darkMode')) || false,
        }
    },

    actions: {
        toggle() {
            this.darkMode = !this.darkMode
            localStorage.setItem('darkMode', this.darkMode)
        }
    }
})
