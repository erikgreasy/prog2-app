import { useAuthStore } from './../stores/auth';
import { useAuth } from '@/composables/auth.js';

export default function auth({to, next, router}) {
    const store = useAuthStore()

    if(!store.loggedIn) {
        router.push({name: 'login'})
    } else {
        return next()
    }
}
