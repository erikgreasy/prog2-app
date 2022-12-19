import { useAuthStore } from './../stores/auth';
import { useAuth } from '@/composables/auth.js';

export default function auth({to, next, router}) {
    const store = useAuthStore()

    const { openLogin } = useAuth()

    if(!store.loggedIn) {
        openLogin()
    } else {
        return next()
    }
}
