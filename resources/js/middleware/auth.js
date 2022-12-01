import { useAuthStore } from './../stores/auth';

export default function auth({to, next, router}) {
    const store = useAuthStore()

    if(to.name != 'login' && !store.loggedIn) {
        console.log('redirect to login')
        router.push('/login')
    } else {
        return next()
    }
}
