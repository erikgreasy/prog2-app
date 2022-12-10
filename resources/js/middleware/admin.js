import { useAuthStore } from './../stores/auth';
import { role } from '../enums/role';

export default function admin({to, next, router}) {
    const store = useAuthStore()

    if(store.user?.role !== role.ADMIN) {
        router.push('/')
    } else {
        return next()
    }
}
