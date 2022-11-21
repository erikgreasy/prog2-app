<template>
    <div>
        <div class="container py-5">
            <nav class="flex">
                <!-- Logo -->
                <AppLogo />
                
                <ul class="flex ml-auto">
                    <li>
                        <router-link :to="{name: 'home'}">Home</router-link>
                    </li>
                    <li>
                        <router-link :to="{name: 'assignments.index'}">Zadania</router-link>
                    </li>
                    <li>
                        <router-link :to="{name: 'admin.dashboard'}">Admin</router-link>
                    </li>
                </ul>
                <!-- <AppButton :to="{name: 'login'}">
                    Prihlásenie
                </AppButton> -->
                <!-- <ul v-if="store.loggedIn" class="ml-auto flex">
                    <li>
                        {{ store.user.name }}
                    </li>
                    <li>
                        <a href="/logout">Logout</a>
                    </li>
                </ul>
                <ul v-else class="ml-auto flex">
                    <li>
                        <router-link :to="{name: 'login'}">Login</router-link>
                    </li>
                </ul> -->
            </nav>
        </div>

        <slot></slot>
    </div>
</template>

<script>
import AppButton from '../../components/AppButton.vue';
import AppLogo from '../../components/AppLogo.vue';
export default {
    components: {
        AppLogo,
        AppButton,
    },

    created() {
        console.log('Public layout created')
    }
}
</script>
