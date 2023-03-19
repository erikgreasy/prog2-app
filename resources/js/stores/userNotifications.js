import { defineStore } from "pinia";

export const useUserNotificationsStore = defineStore('userNotifications', {
    state: () => {
        return {
            notifications: [],
        }
    },

    actions: {
        async getNotifications() {
            try {
                const res = await axios.get(`/api/notifications`)
                
                this.notifications = res.data
                // userNotificationsStore.notifications = res.data
            } catch(err) {
                console.error(err)
                const res = err.response

                if (res?.status === 401) {
                    return
                }
                
                alert('Pri získavaní notifikacii nastala chyba')
            }
        },

        async markAsRead(notificationId) {
            try {
                const res = await axios.post(`/api/notifications/${notificationId}/mark-as-read`)
                
                this.getNotifications()
            } catch(err) {
                console.error(err)
                alert('Pri ukladani notifikacie nastala chyba')
            }
        }
    }
})
