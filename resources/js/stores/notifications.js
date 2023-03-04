import { defineStore } from "pinia";

export const useNotificationsStore = defineStore('notifications', {
    state: () => {
        return {
            id: 1,
            notifications: [],
        }
    },

    actions: {
        addNotification(message, type = 'success') {
            const newId = ++this.id

            this.notifications.push({
                id: newId,
                message: message,
                type: type,
            })

            const self = this
            setTimeout(function () {
                console.log('remove notification')
                self.notifications = self.notifications.filter(notification => notification.id != newId)
            }, 5000)
        }
    }
})
