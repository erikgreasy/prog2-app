import { useRouter } from "vue-router"
import { useAuthStore } from "../stores/auth"

export function useAuth() {
    const router = useRouter()
    const authStore = useAuthStore()

    const logout = async() => {
        try {
            const res = await axios.post('/api/logout')

            authStore.logout()
            
            router.push({name: 'home'})
        } catch(err) {
            console.error(err)
            alert('Pri odhlasovaní nastala chyba')
        }
    }

    const openLogin = () => {
        window.location.href = '/auth/redirect'
    }

    return { logout, openLogin }
}
