import { useAuthStore } from './../stores/auth';

export default function guest({to, next, router}) {
    const store = useAuthStore()

    if(store.loggedIn) {
        router.push('/')
    } else {
        return next()
    }
}
