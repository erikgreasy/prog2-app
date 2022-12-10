import { useAuthStore } from './../stores/auth';
import { role } from '../enums/role';

export default function teacher({to, next, router}) {
    const store = useAuthStore()

    if(store.user?.role !== role.TEACHER && store.user?.role !== role.ADMIN) {
        router.push('/')
    } else {
        return next()
    }
}
