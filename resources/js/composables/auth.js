import { useRouter } from "vue-router"
import { useAuthStore } from "../stores/auth"
import { useUserNotificationsStore } from "@/stores/userNotifications"

export function useAuth() {
    const router = useRouter()
    const authStore = useAuthStore()
    const userNotificationsStore = useUserNotificationsStore()

    const logout = async() => {
        try {
            const res = await axios.post('/api/logout')

            authStore.logout()
            userNotificationsStore.notifications = [] // clear user notifications from store, otherwise they would keep showing until refresh
            
            router.push({name: 'home'})
        } catch(err) {
            console.error(err)
            alert('Pri odhlasovaní nastala chyba')
        }
    }

    const openLogin = () => {
        router.push({name: 'login'})
    }

    return { logout, openLogin }
}
